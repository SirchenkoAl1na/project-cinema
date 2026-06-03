<?php
require_once __DIR__ .'/App' . DIRECTORY_SEPARATOR . 'MobizonApi.php';

$api = new Mobizon\MobizonApi('ua87edc459daa337f44b9acccf27aaf905ad98c4f170c24f18be13eff4df1f3385f5d0', 'api.mobizon.ua');
echo 'Send message...' . PHP_EOL;
$alphaname = 'TEST';
if ($api->call('message',
    'sendSMSMessage',
    array(
        'recipient' => $_POST['phone'],
        'text' => $_POST['text'],
        //Optional, if you don't have registered alphaname, just skip this param and your message will be sent with our free common alphaname.
    ))
) {
    $messageId = $api->getData('messageId');
    echo 'Message created with ID:' . $messageId . PHP_EOL;

    if ($messageId) {
        echo 'Get message info...' . PHP_EOL;
        $messageStatuses = $api->call(
            'message',
            'getSMSStatus',
            array(
                'ids' => array($messageId, '13394', '11345', '4393')
            ),
            array(),
            true
        );

        if ($api->hasData()) {
            foreach ($api->getData() as $messageInfo) {
                echo 'Message # ' . $messageInfo->id . " status:\t" . $messageInfo->status . PHP_EOL;
            }
        }
    }
} else {
    echo 'An error occurred while sending message: [' . $api->getCode() . '] ' . $api->getMessage() . 'See details below:' . PHP_EOL;
    var_dump(array($api->getCode(), $api->getData(), $api->getMessage()));
}