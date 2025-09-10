<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultF5844fca00ffb8bad6b877b3c0a80cf5 extends Migration
{
    protected const DATABASE = 'default';

    private const SCENARIOS = [
        'card' => [
            [
                'title' => 'Success',
                'callbacks' => [
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'callbacks' => [
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                ]
            ],
            [
                'title' => '3ds',
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds Cascading',
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2',
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2 Cascading',
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Clarification',
                'callbacks' => [
                    ['body' => '{"payment": {"status": "awaiting clarification", "method_code": "{{METHOD_CODE}}"}, "clarification": [{"name": "first_name", "type": "text", "validation": {"required": []}}, {"name": "last_name", "type": "text", "validation": {"required": []}}, {"name": "email", "type": "text"}]}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
        ],
        'enthusiast' => [
            [
                'title' => 'Success',
                'callbacks' => [
                    ['body' => '{"aps": {"redirect": {"url": "{{APS_URL}}", "method": "GET"}}, "payment": {"status": "awaiting redirect result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'callbacks' => [
                    ['body' => '{"aps": {"redirect": {"url": "{{APS_URL}}", "method": "GET"}}, "payment": {"status": "awaiting redirect result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
        ],
    ];

    const STATIC_ROUTES = [
        ['name' => 'Saved cards', 'path' => '/customer/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]'],
    ];

    public function up(): void
    {
        $driver = $this->database()->getDriver();

        $this->table('route')
            ->addColumn('id', 'primary')
            ->addColumn('method_id', 'integer')
            ->addColumn('conditions', 'json')
            ->create();

        $this->table('scenario')
            ->addColumn('id', 'primary')
            ->addColumn('route_id', 'integer')
            ->addColumn('title', 'string')
            ->create();

        $this->table('callback')
            ->addColumn('id', 'primary')
            ->addColumn('scenario_id', 'integer')
            ->addColumn('body', 'json')
            ->addColumn('order', 'tinyint')
            ->create();

        $this->table('static')
            ->addColumn('id', 'primary')
            ->addColumn('name', 'string')
            ->addColumn('path', 'string')
            ->addColumn('body', 'json')
            ->create();

        $driver->execute("INSERT INTO route (method_id, conditions) SELECT id, '{}' FROM method");

        foreach (self::SCENARIOS as $methodCode => $scenarios) {
            foreach ($scenarios as $scenario) {
                $title = $scenario['title'];
                $driver->execute("
                    INSERT INTO scenario (route_id, title)
                    SELECT
                        r.id, '$title'
                    FROM route r
                        JOIN method m ON m.id = r.method_id
                    WHERE m.code = '$methodCode';
                ");

                foreach ($scenario['callbacks'] as $callback) {
                    $body = $callback['body'];
                    $order = $callback['order'];
                    $driver->execute("
                        INSERT INTO `callback` (scenario_id, body, `order`)
                        SELECT
                            s.id, '$body', $order
                        FROM scenario s
                            JOIN route r ON r.id = s.route_id
                            JOIN method m ON m.id = r.method_id
                        WHERE m.code = '$methodCode' AND s.title = '$title';
                    ");
                }
            }
        }

        foreach (self::STATIC_ROUTES as $static) {
            $name = $static['name'];
            $path = $static['path'];
            $body = $static['body'];

            $driver->execute("INSERT INTO `static` (name, path, body) VALUES ('$name', '$path', '$body');");
        }
    }

    public function down(): void
    {
        $driver = $this->database()->getDriver();

        $driver->execute('DROP TABLE IF EXISTS `callback`');
        $driver->execute('DROP TABLE IF EXISTS `scenario`');
        $driver->execute('DROP TABLE IF EXISTS `route`');
        $driver->execute('DROP TABLE IF EXISTS `static`');
    }
}
