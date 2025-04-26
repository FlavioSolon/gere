<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class OfficeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $office = $user->office;

        return Inertia::render('Office/Dashboard', [
            'office' => $office ? [
                'razao_social' => $office->razao_social,
                'cnpj' => $office->cnpj,
                'balance' => $office->balance,
                'subscription_cnpjs' => $office->subscription_cnpjs,
            ] : null,
            'clients' => $office ? $office->clients()->get(['id', 'cnpj', 'razao_social'])->toArray() : [],
            'error' => $office ? null : 'Nenhum escritório associado ao usuário. Contate o suporte.',
        ]);
    }

    public function storeClient(Request $request)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }

        $validated = $request->validate([
            'cnpj' => [
                'required',
                'string',
                'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/',
                'unique:clients,cnpj,NULL,id,office_id,' . $office->id,
            ],
            'razao_social' => 'required|string|max:255',
        ]);

        $office->clients()->create([
            'id' => (string) Str::uuid(),
            'cnpj' => $validated['cnpj'],
            'razao_social' => $validated['razao_social'],
            'office_id' => $office->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Cliente adicionado com sucesso!');
    }

    public function downloadReport(Request $request, $clientId)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }

        $client = Client::where('id', $clientId)->where('office_id', $office->id)->first();

        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Cliente não encontrado ou não pertence ao escritório.');
        }

        // Preparar dados para a API
        $baseUrl = config('services.serpro.trial_base_url', env('SERPRO_API_TRIAL_BASE_URL'));
        $token = config('services.serpro.trial_token', env('SERPRO_API_TRIAL_TOKEN'));
        $contratanteCnpj = config('services.serpro.contratante_cnpj', env('SERPRO_CONTRATANTE_CNPJ'));
        $autorCnpj = config('services.serpro.autor_cnpj', env('SERPRO_AUTOR_CNPJ'));

        // Normalizar CNPJ do cliente (remover pontos, barras e traços)
        $clientCnpj = preg_replace('/[\.\-\/]/', '', $client->cnpj);

        // Passo 1: Solicitar protocolo
        $protocolResponse = Http::withHeaders([
            'Accept' => 'text/plain',
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post("$baseUrl/Apoiar", [
            'contratante' => [
                'numero' => $contratanteCnpj,
                'tipo' => 2,
            ],
            'autorPedidoDados' => [
                'numero' => $autorCnpj,
                'tipo' => 2,
            ],
            'contribuinte' => [
                'numero' => $clientCnpj,
                'tipo' => 2, // CNPJ
            ],
            'pedidoDados' => [
                'idSistema' => 'SITFIS',
                'idServico' => 'SOLICITARPROTOCOLO91',
                'versaoSistema' => '1.0',
                'dados' => '',
            ],
        ]);

        if ($protocolResponse->failed()) {
            \Log::error('Falha ao solicitar protocolo SITFIS', [
                'status' => $protocolResponse->status(),
                'body' => $protocolResponse->body(),
            ]);
            return redirect()->route('dashboard')->with('error', 'Erro ao solicitar protocolo do relatório fiscal. Tente novamente.');
        }

        $protocolData = $protocolResponse->json();
        $protocoloRelatorio = $protocolData['protocolo'] ?? null;

        if (!$protocoloRelatorio) {
            \Log::error('Protocolo não retornado pela API SITFIS', ['response' => $protocolData]);
            return redirect()->route('dashboard')->with('error', 'Protocolo do relatório não encontrado. Tente novamente.');
        }

        // Passo 2: Emitir relatório
        $reportResponse = Http::withHeaders([
            'Accept' => 'text/plain',
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post("$baseUrl/Emitir", [
            'contratante' => [
                'numero' => $contratanteCnpj,
                'tipo' => 2,
            ],
            'autorPedidoDados' => [
                'numero' => $autorCnpj,
                'tipo' => 2,
            ],
            'contribuinte' => [
                'numero' => $clientCnpj,
                'tipo' => 2,
            ],
            'pedidoDados' => [
                'idSistema' => 'SITFIS',
                'idServico' => 'RELATORIOSITFIS92',
                'versaoSistema' => '1.0',
                'dados' => json_encode(['protocoloRelatorio' => $protocoloRelatorio]),
            ],
        ]);

        if ($reportResponse->failed()) {
            \Log::error('Falha ao emitir relatório SITFIS', [
                'status' => $reportResponse->status(),
                'body' => $reportResponse->body(),
            ]);
            return redirect()->route('dashboard')->with('error', 'Erro ao emitir o relatório fiscal. Tente novamente.');
        }

        $reportData = $reportResponse->json();
        $reportBase64 = $reportData['relatorio'] ?? null;

        if (!$reportBase64) {
            \Log::error('Relatório não retornado pela API SITFIS', ['response' => $reportData]);
            return redirect()->route('dashboard')->with('error', 'Relatório fiscal não encontrado. Tente novamente.');
        }

        // Decodificar o relatório (assumindo que é um PDF em base64)
        $reportContent = base64_decode($reportBase64);

        // Gerar nome do arquivo
        $fileName = 'relatorio_sitfis_' . $clientCnpj . '_' . now()->format('YmdHis') . '.pdf';

        // Retornar o arquivo para download
        return response($reportContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }

    public function testApi(Request $request)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }

        if ($request->isMethod('get')) {
            return Inertia::render('Office/TestApi', [
                'office' => [
                    'razao_social' => $office->razao_social,
                    'cnpj' => $office->cnpj,
                ],
                'default_cnpj' => '99999999999', // CNPJ de teste
            ]);
        }

        // Processar requisição POST
        $validated = $request->validate([
            'cnpj' => [
                'required',
                'string',
                'regex:/^(\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}|\d{11})$/',
            ],
        ]);

        $cnpj = preg_replace('/[\.\-\/]/', '', $validated['cnpj']);
        $tipo = strlen($cnpj) === 11 ? 1 : 2; // CPF (11 dígitos) ou CNPJ (14 dígitos)

        // Configurações da API
        $baseUrl = config('services.serpro.trial_base_url');
        $token = config('services.serpro.trial_token');
        $contratanteCnpj = config('services.serpro.contratante_cnpj');
        $autorCnpj = config('services.serpro.autor_cnpj');

        // Log para depuração
        \Log::info('Configurações da API Serpro carregadas', [
            'base_url' => $baseUrl,
            'token' => $token,
            'contratante_cnpj' => $contratanteCnpj,
            'autor_cnpj' => $autorCnpj,
        ]);

        // Validar configurações
        if (!$baseUrl || !$token || !$contratanteCnpj || !$autorCnpj) {
            \Log::error('Configurações da API Serpro incompletas', [
                'base_url' => $baseUrl,
                'token' => $token,
                'contratante_cnpj' => $contratanteCnpj,
                'autor_cnpj' => $autorCnpj,
            ]);
            return redirect()->route('office.test-api')->with('error', 'Configurações da API inválidas. Contate o suporte.');
        }

        // Passo 1: Solicitar protocolo
        $protocolResponse = Http::withHeaders([
            'Accept' => 'text/plain',
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post(rtrim($baseUrl, '/') . '/Apoiar', [
            'contratante' => [
                'numero' => $contratanteCnpj,
                'tipo' => 2,
            ],
            'autorPedidoDados' => [
                'numero' => $autorCnpj,
                'tipo' => 2,
            ],
            'contribuinte' => [
                'numero' => $cnpj,
                'tipo' => $tipo,
            ],
            'pedidoDados' => [
                'idSistema' => 'SITFIS',
                'idServico' => 'SOLICITARPROTOCOLO91',
                'versaoSistema' => '1.0',
                'dados' => '',
            ],
        ]);

        if ($protocolResponse->failed()) {
            \Log::error('Falha ao solicitar protocolo SITFIS', [
                'status' => $protocolResponse->status(),
                'body' => $protocolResponse->body(),
                'cnpj' => $cnpj,
            ]);
            return redirect()->route('office.test-api')->with('error', 'Erro ao solicitar protocolo: ' . $protocolResponse->body());
        }

        $protocolData = $protocolResponse->json();
        \Log::info('Resposta da API Apoiar', ['response' => $protocolData]);

        // Decodificar o campo 'dados' que contém o protocolo
        $dados = isset($protocolData['dados']) ? json_decode($protocolData['dados'], true) : null;
        if (!$dados || !isset($dados['protocoloRelatorio'])) {
            \Log::error('Protocolo não retornado pela API SITFIS', ['response' => $protocolData]);
            return redirect()->route('office.test-api')->with('error', 'Protocolo do relatório não encontrado.');
        }

        $protocoloRelatorio = $dados['protocoloRelatorio'];

        // Passo 2: Emitir relatório
        $reportResponse = Http::withHeaders([
            'Accept' => 'text/plain',
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post(rtrim($baseUrl, '/') . '/Emitir', [
            'contratante' => [
                'numero' => $contratanteCnpj,
                'tipo' => 2,
            ],
            'autorPedidoDados' => [
                'numero' => $autorCnpj,
                'tipo' => 2,
            ],
            'contribuinte' => [
                'numero' => $cnpj,
                'tipo' => $tipo,
            ],
            'pedidoDados' => [
                'idSistema' => 'SITFIS',
                'idServico' => 'RELATORIOSITFIS92',
                'versaoSistema' => '1.0',
                'dados' => json_encode(['protocoloRelatorio' => $protocoloRelatorio]),
            ],
        ]);

        if ($reportResponse->failed()) {
            \Log::error('Falha ao emitir relatório SITFIS', [
                'status' => $reportResponse->status(),
                'body' => $reportResponse->body(),
                'cnpj' => $cnpj,
            ]);
            return redirect()->route('office.test-api')->with('error', 'Erro ao emitir relatório: ' . $reportResponse->body());
        }

        $reportData = $reportResponse->json();
        \Log::info('Resposta da API Emitir', ['response' => $reportData]);

        // Decodificar o campo 'dados' que contém o PDF
        $reportDados = isset($reportData['dados']) ? json_decode($reportData['dados'], true) : null;
        if (!$reportDados || !isset($reportDados['pdf'])) {
            \Log::error('Relatório não retornado pela API SITFIS', ['response' => $reportData]);
            return redirect()->route('office.test-api')->with('error', 'Relatório não encontrado.');
        }

        $reportBase64 = $reportDados['pdf'];

        // Decodificar o relatório (assumindo PDF em base64)
        $reportContent = base64_decode($reportBase64, true);
        if ($reportContent === false) {
            \Log::error('Falha ao decodificar o relatório base64', ['base64' => substr($reportBase64, 0, 100)]);
            return redirect()->route('office.test-api')->with('error', 'Erro ao processar o relatório.');
        }

        // Validar se o conteúdo é um PDF válido
        if (substr($reportContent, 0, 4) !== '%PDF') {
            \Log::error('Conteúdo decodificado não é um PDF válido', ['content_preview' => substr($reportContent, 0, 100)]);
            return redirect()->route('office.test-api')->with('error', 'O relatório retornado não é um PDF válido.');
        }

        $fileName = 'relatorio_sitfis_test_' . $cnpj . '_' . now()->format('YmdHis') . '.pdf';

        // Forçar o download do PDF
        return response($reportContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($reportContent),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function edit(Request $request)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }


        return Inertia::render('Office/EditOffice', [
            'office' => [
                'id' => $office->id,
                'cnpj' => $office->cnpj,
                'razao_social' => $office->razao_social,
                'certificate_path' => $office->certificate_path,
                'certificate_password' => $office->certificate_password ?: '', // Garante string vazia se null
                'balance' => $office->balance,
                'subscription_cnpjs' => $office->subscription_cnpjs,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            \Log::error('Nenhum escritório associado ao usuário', ['user_id' => $user->id]);
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }

        // Validação dos dados
        $validated = $request->validate([
            'cnpj' => [
                'required',
                'string',
                'regex:/^(\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}|\d{14})$/',
                function ($attribute, $value, $fail) {
                    if (!$this->validateCnpj($value)) {
                        $fail('O CNPJ é inválido.');
                    }
                },
            ],
            'razao_social' => 'required|string|max:255',
            'certificate' => 'nullable|file|mimes:pfx,p12|max:5120',
            'certificate_password' => 'required_with:certificate|string|min:1|max:255',
        ]);

        // Preparar dados para atualização
        $data = [
            'cnpj' => preg_replace('/[\.\-\/]/', '', $validated['cnpj']),
            'razao_social' => $validated['razao_social'],
        ];

        // Determinar o disco com base no ambiente
        $disk = config('filesystems.default'); // Usa FILESYSTEM_DISK (s3 ou local)
        \Log::info('Disco selecionado para armazenamento', ['disk' => $disk]);

        // Processar o certificado, se enviado
        if ($request->hasFile('certificate')) {
            $certificate = $request->file('certificate');
            if (!$certificate->isValid()) {
                \Log::error('Arquivo de certificado inválido', ['user_id' => $user->id]);
                return redirect()->route('office.edit')->with('error', 'O arquivo do certificado é inválido.');
            }

            $cleanCnpj = preg_replace('/[\.\-\/]/', '', $validated['cnpj']);
            $fileName = "certificates/office_{$cleanCnpj}_" . now()->format('YmdHis') . '.' . $certificate->getClientOriginalExtension();

            // Salvar no disco correto (s3 ou local)
            $path = $certificate->storeAs('', $fileName, $disk);
            if (!$path) {
                \Log::error('Falha ao salvar o certificado', [
                    'disk' => $disk,
                    'cnpj' => $cleanCnpj,
                    'file' => $fileName,
                ]);
                return redirect()->route('office.edit')->with('error', 'Erro ao salvar o certificado. Tente novamente.');
            }

            // Deletar o certificado antigo, se existir
            if ($office->certificate_path) {
                \Log::info('Deletando certificado antigo', ['old_path' => $office->certificate_path]);
                Storage::disk($disk)->delete($office->certificate_path);
            }

            $data['certificate_path'] = $fileName;
            $data['certificate_password'] = $validated['certificate_password'];

            \Log::info('Certificado digital atualizado', [
                'cnpj' => $cleanCnpj,
                'path' => $fileName,
                'disk' => $disk,
            ]);
        } elseif ($request->filled('certificate_password')) {
            // Atualizar a senha se fornecida sem novo certificado
            $data['certificate_password'] = $validated['certificate_password'];
            \Log::info('Senha do certificado atualizada sem novo certificado', [
                'office_id' => $office->id,
                'certificate_password' => $validated['certificate_password'],
            ]);
        }

        // Atualizar o escritório
        $office->update($data);

        \Log::info('Escritório atualizado', [
            'office_id' => $office->id,
            'data' => $data,
        ]);

        return redirect()->route('office.edit')->with('success', 'Dados do escritório atualizados com sucesso!');
    }


    /**
     * Validar CNPJ manualmente.
     */
    private function validateCnpj($cnpj)
    {
        $cnpj = preg_replace('/[\.\-\/]/', '', $cnpj);

        if (strlen($cnpj) != 14 || !is_numeric($cnpj)) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights1[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ($cnpj[12] != $digit1) {
            return false;
        }

        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights2[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return $cnpj[13] == $digit2;
    }
}
