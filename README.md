# CLOUDSPACE AML

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cloudspace/aml?style=flat-square)](https://packagist.org/packages/cloudspace/aml)
[![Known Vulnerabilities](https://snyk.io/test/github/ikechukwukalu/cloudspaceaml/badge.svg?style=flat-square)](https://security.snyk.io/package/composer/ikechukwukalu%2Fcloudspaceaml)
[![Github Workflow Status](https://img.shields.io/github/actions/workflow/status/ikechukwukalu/cloudspaceaml/cloudspaceaml.yml?branch=main&style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/actions/workflows/cloudspaceaml.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/cloudspace/aml?style=flat-square)](https://packagist.org/packages/cloudspace/aml)
[![GitHub Repo stars](https://img.shields.io/github/stars/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/stargazers)
[![GitHub issues](https://img.shields.io/github/issues/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/issues)
[![GitHub forks](https://img.shields.io/github/forks/ikechukwukalu/cloudspaceaml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/forks)
[![Licence](https://img.shields.io/packagist/l/cloudspace/aml?style=flat-square)](https://github.com/ikechukwukalu/cloudspaceaml/blob/main/LICENSE.md)

Laravel package for AML (Anti-Money Laundering) logic including PII-based intelligence, risk scanning, sanctions matching, and red-flag detection for Nigerian financial applications.

## REQUIREMENTS

- PHP 8.0+
- Laravel 10+

## STEPS TO INSTALL

``` shell
composer require cloudspace/aml
```

## USAGE BY FACADE

```php
use Cloudspace\AML\Facades\AML;

$response = AML::checkSanctions([
    'name' => 'Nicolas Maduro',
    'birthDate' => '1962-11-23',
    'gender' => 'male',

    /**
     * Optional Array Fields
     */
    'address' => [
        '17 Maryland St, Boston, MA 02125, USA'
    ],
    'phone' => [
        '+19037382902'
    ],
    'email' => [
        'johndoe@xyz.co'
    ],
    'website' => [
        'https://xyz.co'
    ]
]);

dd($response);
```

## USAGE BY API

```js
fetch('http://127.0.0.1:8000/api/aml/check', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        'name': 'Nicolas Maduro',
        'birthDate': '1962-11-23',
        'gender': 'male',

        /**
         * Optional Array Fields
         */
        'address': {
            '17 Maryland St, Boston, MA 02125, USA'
        },
        'phone': {
            '+19037382902'
        },
        'email': {
            'johndoe@xyz.co'
        },
        'website': {
            'https://xyz.co'
        }
    })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

Publish the config and migration files:

```bash
php artisan vendor:publish --provider="Cloudspace\AML\Providers\AmlServiceProvider"
php artisan migrate
```

---

## Configuration

Update your `.env` file:

```env
AML_WEB_SEARCH_DRIVER=bing
BING_SEARCH_API_KEY=your_bing_key
CONTEXTUAL_API_KEY=your_contextual_key
AML_ALERT_EMAIL=your_compliance_email@domain.com
```

---

## USAGE BY FACADE

### 🧠 PII-Based Risk Scan

```php
use Cloudspace\AML\Facades\RiskScanner;

$response = RiskScanner::scan(
    'John Doe',
    '12345678901', // BVN
    '10987654321'  // NIN
);

dd($response->toArray());
```

**Sample Output:**

```php
[
    'id' => 1,
    'full_name' => 'John Doe',
    'risk_level' => 'high',
    'matches' => [
        [
            'source' => 'Bing',
            'match_type' => 'media mention',
            'confidence' => 85,
            'description' => 'EFCC arrests John Doe in money laundering case...'
        ]
    ]
]
```

---

## USAGE BY API

### 📡 Risk Scan via API

```http
POST /api/aml/scan-user
```

**Body:**

```json
{
  "name": "John Doe",
  "bvn": "12345678901",
  "nin": "10987654321"
}
```

**Response:**

```json
{
  "risk_level": "high",
  "matches": [
    {
      "source": "Bing",
      "match_type": "media mention",
      "description": "John Doe linked to EFCC arrest",
      "confidence": 85
    }
  ]
}
```

### 🧾 List Risk Scan History

```http
GET /api/aml/scans?risk_level=high&name=John&from=2025-04-01
```

Returns paginated risk scan history with match breakdowns.

### 🧾 Download PDF Risk Report

```http
GET /api/aml/scans/{id}/pdf
```

Generates a full PDF report with user scan result and confidence levels.

---

## ⏰ Daily Auto-Scanning of New Users

Scans newly created users every day at 2 AM (scheduler required):

```bash
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

---

## 📧 High-Risk Email Alerts

If `risk_level` is `high`, an alert email will be sent to the address in your `.env`:

```env
AML_ALERT_EMAIL=your@compliance.email
```

> Email View: `aml::emails.risk-alert`

---

## LICENSE

The CloudSpaceAML package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
