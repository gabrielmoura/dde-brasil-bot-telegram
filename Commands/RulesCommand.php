<?php declare(strict_types=1);
/**
 * This file is part of the PHP Telegram Support Bot.
 *
 * (c) PHP Telegram Bot Team (https://github.com/php-telegram-bot)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * User "/rules" command
 */
class RulesCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'rules';

    /**
     * @var string
     */
    protected $description = 'Show the PHP Telegram Support Bot rules';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * @var string
     */
    protected $usage = '/rules';

    /**
     * @var bool
     */

    protected $show_in_help = true;
    protected $private_only = false;
    /**
     * @inheritdoc
     */
    public function execute(): ServerResponse
    {
        $text = <<<EOT
Regras: `Apenas em português | Sem Spamming ou Sem Sentido | Sem bots
¬ ** Apenas em português **
Por favor, mantenha suas conversas em português dentro desta sala de chat, caso contrário sua mensagem será apagada
¬ ** Nenhum spam ou disparates sem sentido **
Não envie etiquetas ou envie mensagens com conteúdo inútil. Quando repetido você pode ser chutado ou banido
¬ ** Sem bots **
Por favor, não adicione um bot dentro deste bate-papo sem pedir primeiro aos administradores. Sinta-se livre para mencionar o Bot em uma mensagem
EOT;
        return $this->replyToChat($text, ['parse_mode' => 'markdown']);
    }
}