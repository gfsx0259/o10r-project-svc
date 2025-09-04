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
                'callbacks' => [
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'conditions' => ['payment.amount' => 1001],
                'callbacks' => [
                    ['body' => '{"payment": {"status": "failed", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                ]
            ],
            [
                'title' => '3ds',
                'conditions' => ['payment.amount' => 1002],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds Cascading',
                'conditions' => ['payment.amount' => 1003],
                'callbacks' => [
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"md": "{{MD}}", "pa_req": "string", "term_url": "{{TERM_URL}}"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2',
                'conditions' => ['payment.amount' => 1004],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => '3ds2 Cascading',
                'conditions' => ['payment.amount' => 1005],
                'callbacks' => [
                    ['body' => '{"acs": {"iframe": {"url": "{{ACS_URL}}", "body": {"3DSMethodData": "string", "threeDSMethodData": "string"}, "method": "POST"}}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"acs": {"redirect": {"url": "{{ACS_URL}}", "body": {"creq": "string", "threeDSSessionData": "string"}, "method": "POST"}, "is_cascading": true}, "account": {"number": "4242 **** **** **42", "card_holder": "FDSA FDASF", "expiry_year": "33", "expiry_month": "01"}, "payment": {"status": "awaiting 3ds result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Clarification',
                'conditions' => ['payment.amount' => 1006],
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
                'callbacks' => [
                    ['body' => '{"aps": {"redirect": {"url": "{{APS_URL}}", "method": "GET"}}, "payment": {"status": "awaiting redirect result", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0],
                    ['body' => '{"payment": {"status": "success", "method_code": "{{METHOD_CODE}}"}}', 'order' => 0]
                ]
            ],
            [
                'title' => 'Fail',
                'conditions' => ['payment.amount' => 1001],
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
            }
        }
    }

    public function down(): void
    {
        $this->table('route')
            ->drop();
        $this->table('scenario')
            ->drop();
        $this->table('callback')
            ->drop();
    }
}
