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
                'conditions' => ['payment.amount' => 1000],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'conditions' => ['payment.amount' => 1001],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                ]
            ],
            [
                'title' => '3ds',
                'conditions' => ['payment.amount' => 1002],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds Cascading',
                'conditions' => ['payment.amount' => 1003],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2',
                'conditions' => ['payment.amount' => 1004],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2 Cascading',
                'conditions' => ['payment.amount' => 1005],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Clarification',
                'conditions' => ['payment.amount' => 1006],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "awaiting clarification", "method_code": "{{METHOD_CODE}}"}, "clarification": [{"name": "first_name", "type": "text", "validation": {"required": []}}, {"name": "last_name", "type": "text", "validation": {"required": []}}, {"name": "email", "type": "text"}]}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Success with saved cards',
                'conditions' => ['payment.amount' => 1007, 'customer.id' => 1],
                'static' => [
                    ['path' => '/customer/1/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail with saved cards',
                'conditions' => ['payment.amount' => 1008, 'customer.id' => 2],
                'static' => [
                    ['path' => '/customer/2/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                ]
            ],
            [
                'title' => '3ds with saved cards',
                'conditions' => ['payment.amount' => 1009, 'customer.id' => 3],
                'static' => [
                    ['path' => '/customer/3/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds Cascading with saved cards',
                'conditions' => ['payment.amount' => 1010, 'customer.id' => 4],
                'static' => [
                    ['path' => '/customer/4/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2 with saved cards',
                'conditions' => ['payment.amount' => 1011, 'customer.id' => 5],
                'static' => [
                    ['path' => '/customer/5/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2 Cascading with saved cards',
                'conditions' => ['payment.amount' => 1012, 'customer.id' => 6],
                'static' => [
                    ['path' => '/customer/6/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Clarification with saved cards',
                'conditions' => ['payment.amount' => 1013, 'customer.id' => 7],
                'static' => [
                    ['path' => '/customer/7/cards', 'body' => '[{"id":1,"last_four_digits":"4211","type":"visa","expiry_month":"11","expiry_year":"25"},{"id":2,"last_four_digits":"1233","type":"mastercard","expiry_month":"09","expiry_year":"26"},{"id":3,"last_four_digits":"3781","type":"visa","expiry_month":"01","expiry_year":"25"}]']
                ],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "awaiting clarification", "method_code": "{{METHOD_CODE}}"}, "clarification": [{"name": "first_name", "type": "text", "validation": {"required": []}}, {"name": "last_name", "type": "text", "validation": {"required": []}}, {"name": "email", "type": "text"}]}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
        ],
        'enthusiast' => [
            [
                'title' => 'Success',
                'conditions' => ['payment.amount' => 1000],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"aps": {"redirect": {"url": "{{APS_URL}}", "method": "GET"}}, "payment": {"status": "awaiting redirect result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'conditions' => ['payment.amount' => 1001],
                'static' => [],
                'callbacks' => [
                    ['body' => '{"aps": {"redirect": {"url": "{{APS_URL}}", "method": "GET"}}, "payment": {"status": "awaiting redirect result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
        ],
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
            ->addColumn('conditions', 'json')
            ->create();

        $this->table('callback')
            ->addColumn('id', 'primary')
            ->addColumn('scenario_id', 'integer')
            ->addColumn('body', 'json')
            ->addColumn('order', 'tinyint')
            ->create();

        $this->table('static')
            ->addColumn('id', 'primary')
            ->addColumn('scenario_id', 'integer')
            ->addColumn('path', 'string')
            ->addColumn('body', 'json')
            ->create();

        $driver->execute("INSERT INTO route (method_id, conditions) SELECT id, '{}' FROM method");

        foreach (self::SCENARIOS as $methodCode => $scenarios) {
            foreach ($scenarios as $scenario) {
                $title = $scenario['title'];
                $conditions = json_encode($scenario['conditions']);
                $driver->execute("
                    INSERT INTO scenario (route_id, title, conditions)
                    SELECT
                        r.id, '$title', '$conditions'
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

                foreach ($scenario['static'] as $static) {
                    $path = $static['path'];
                    $body = $static['body'];
                    $driver->execute("
                        INSERT INTO `static` (scenario_id, path, body)
                        SELECT
                            s.id, '$path', '$body'
                        FROM scenario s
                            JOIN route r ON r.id = s.route_id
                            JOIN method m ON m.id = r.method_id
                        WHERE m.code = '$methodCode' AND s.title = '$title';
                    ");
                }
            }
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
