<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class ViettelEinvoiceService
{
    public function getPublishSalePath(): string
    {
        return (string) config('services.viettel_einvoice.publish_sale_path', '/invoices/publish');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.viettel_einvoice.enabled', false);
    }

    public function shouldPublishOnSale(): bool
    {
        return (bool) config('services.viettel_einvoice.publish_on_sale', false);
    }

    public function getSyncMode(): string
    {
        return (string) config('services.viettel_einvoice.sync_mode', 'sync');
    }

    public function getBaseUrl(): string
    {
        return rtrim((string) config('services.viettel_einvoice.base_url', ''), '/');
    }

    public function getApiUrl(): string
    {
        $apiUrl = (string) config('services.viettel_einvoice.api_url', '');

        if ($apiUrl !== '') {
            return rtrim($apiUrl, '/');
        }

        $baseUrl = $this->getBaseUrl();

        return $baseUrl === '' ? '' : $baseUrl.'/api';
    }

    public function getTimeout(): int
    {
        return (int) config('services.viettel_einvoice.timeout', 30);
    }

    public function shouldVerifySsl(): bool
    {
        return (bool) config('services.viettel_einvoice.verify_ssl', true);
    }

    public function getCredentials(): array
    {
        return [
            'username' => (string) config('services.viettel_einvoice.username'),
            'password' => (string) config('services.viettel_einvoice.password'),
            'token' => (string) config('services.viettel_einvoice.token'),
            'client_id' => (string) config('services.viettel_einvoice.client_id'),
            'client_secret' => (string) config('services.viettel_einvoice.client_secret'),
        ];
    }

    public function getCompanyConfig(): array
    {
        return [
            'company_code' => (string) config('services.viettel_einvoice.company_code'),
            'branch_code' => (string) config('services.viettel_einvoice.branch_code'),
            'template_code' => (string) config('services.viettel_einvoice.template_code'),
            'invoice_series' => (string) config('services.viettel_einvoice.invoice_series'),
            'invoice_type' => (string) config('services.viettel_einvoice.invoice_type', '01GTKT'),
            'payment_method' => (string) config('services.viettel_einvoice.default_payment_method', 'TM/CK'),
        ];
    }

    public function hasTokenAuth(): bool
    {
        return $this->getCredentials()['token'] !== '';
    }

    public function hasBasicAuth(): bool
    {
        $credentials = $this->getCredentials();

        return $credentials['username'] !== '' && $credentials['password'] !== '';
    }

    public function hasClientCredentials(): bool
    {
        $credentials = $this->getCredentials();

        return $credentials['client_id'] !== '' && $credentials['client_secret'] !== '';
    }

    public function validateConfig(): void
    {
        if (!$this->isEnabled()) {
            throw new InvalidArgumentException('Viettel eInvoice is disabled.');
        }

        if ($this->getApiUrl() === '') {
            throw new InvalidArgumentException('Viettel eInvoice API URL is not configured.');
        }

        if (!$this->hasTokenAuth() && !$this->hasBasicAuth() && !$this->hasClientCredentials()) {
            throw new InvalidArgumentException('Viettel eInvoice credentials are not configured.');
        }
    }

    public function getDefaultHeaders(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $credentials = $this->getCredentials();
        $company = $this->getCompanyConfig();

        if ($credentials['token'] !== '') {
            $headers['Authorization'] = 'Bearer '.$credentials['token'];
        }

        if ($company['company_code'] !== '') {
            $headers['X-Company-Code'] = $company['company_code'];
        }

        return $headers;
    }

    public function makeHttpClient(): PendingRequest
    {
        $client = Http::baseUrl($this->getApiUrl())
            ->acceptJson()
            ->timeout($this->getTimeout())
            ->withOptions([
                'verify' => $this->shouldVerifySsl(),
            ])
            ->withHeaders($this->getDefaultHeaders());

        if ($this->hasBasicAuth()) {
            $credentials = $this->getCredentials();
            $client = $client->withBasicAuth($credentials['username'], $credentials['password']);
        }

        return $client;
    }

    public function buildSaleInvoicePayload(array $data): array
    {
        $company = $this->getCompanyConfig();

        return [
            'company_code' => $company['company_code'],
            'branch_code' => $company['branch_code'],
            'template_code' => $company['template_code'],
            'invoice_series' => $company['invoice_series'],
            'invoice_type' => $company['invoice_type'],
            'payment_method' => $data['payment_method'] ?? $company['payment_method'],
            'transaction_code' => $data['transaction_code'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? null,
            'buyer' => $data['buyer'] ?? [],
            'items' => $data['items'] ?? [],
            'total_amount' => $data['total_amount'] ?? 0,
            'note' => $data['note'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ];
    }

    public function publishSaleInvoice(array $data): array
    {
        $this->validateConfig();

        $payload = $this->buildSaleInvoicePayload($data);
        $response = $this->makeHttpClient()->post($this->getPublishSalePath(), $payload);
        $responseData = $response->json();

        if (!$response->ok()) {
            throw new InvalidArgumentException(
                (string) data_get($responseData, 'message', 'Viettel eInvoice publish request failed.')
            );
        }

        return [
            'status' => (bool) data_get($responseData, 'status', true),
            'message' => (string) data_get($responseData, 'message', 'Publish sale invoice success.'),
            'invoice_no' => data_get($responseData, 'data.invoice_no', data_get($responseData, 'invoice_no')),
            'invoice_url' => data_get($responseData, 'data.invoice_url', data_get($responseData, 'invoice_url')),
            'lookup_code' => data_get($responseData, 'data.lookup_code', data_get($responseData, 'lookup_code')),
            'raw' => $responseData,
            'payload' => $payload,
        ];
    }
}
