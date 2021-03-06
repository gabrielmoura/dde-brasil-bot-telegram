<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Helpers\Helpers;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ChatMember;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Request;

/**
 * New chat member command
 */
class NewchatmembersCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'newchatmembers';

    /**
     * @var string
     */
    protected $description = 'New Chat Members';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    protected $chat_id;
    protected $user_id;

    /**
     * @var string
     */
    protected $group_name = 'DeepinBrasilBot';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $this->chat_id = $message->getChat()->getId();
        $this->user_id = $message->getFrom()->getId();
        $this->group_name = $message->getChat()->getTitle();
        //['users' => $new_users, 'bots' => $new_bots] = $this->getNewUsersAndBots();
        // Kick bots if they weren't added by an admin.
        //$this->kickDisallowedBots($new_users, $chat_id);
        //return $this->refreshWelcomeMessage($new_bots);
    }

    /**
     * Remove existing and send new welcome message.
     *
     * @param array $new_users
     *
     * @return ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected function refreshWelcomeMessage(array $new_users): ServerResponse
    {
        if (empty($new_users)) {
            return Request::emptyResponse();
        }
        $new_users_text = implode(', ', array_map(function (User $new_user) {
            return '<a href="tg://user?id=' . $new_user->getId() . '">' . filter_var($new_user->getFirstName(),
                    FILTER_SANITIZE_SPECIAL_CHARS) . '</a>';
        }, $new_users));
        $text = "Bem vindo {$new_users_text} ao grupo <b>{$this->group_name}</b> \n";
        $text .= 'Please remember that this is <b>NOT</b> the Telegram Support Chat.' . PHP_EOL;
        $text .= 'Read the <a href="https://t.me/PHP_Telegram_Bot_Support/5526">Rules</a> that apply here.';
        $welcome_message_sent = $this->replyToChat($text, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);
        if (!$welcome_message_sent->isOk()) {
            return Request::emptyResponse();
        }
        $welcome_message = $welcome_message_sent->getResult();
        $new_message_id = $welcome_message->getMessageId();
        $chat_id = $welcome_message->getChat()->getId();
        if ($new_message_id && $chat_id) {
            Helpers::saveLatestWelcomeMessage($new_message_id);
            Helpers::deleteOldWelcomeMessages();
        }
        return $welcome_message_sent;
    }

    /**
     * Check if the bot has been added by an admin.
     *
     * @return bool
     */
    protected function isUserAllowedToAddBot(): bool
    {
        $chat_member = Request::getChatMember([
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
        ])->getResult();
        if ($chat_member instanceof ChatMember) {
            return \in_array($chat_member->getStatus(), ['creator', 'administrator'], true);
        }
        return false;
    }

    /**
     * Get an array of all newly added users and bots.
     *
     * @return array
     */
    protected function getNewUsersAndBots(): array
    {
        $users = [];
        $bots = [];
        foreach ($this->getMessage()->getNewChatMembers() as $member) {
            if ($member->getIsBot()) {
                $bots[] = $member;
                continue;
            }
            $users[] = $member;
        }
        return compact('users', 'bots');
    }

    /**
     * Kick bots that weren't added by an admin.
     *
     * @todo: Maybe notify the admins / user that tried to add the bot(s)?
     *
     * @param array $bots
     */
    protected function kickDisallowedBots(array $bots, $chat_id = null): void
    {
        if (empty($bots) || $this->isUserAllowedToAddBot()) {
            return;
        }
        foreach ($bots as $bot) {
            $x = Request::kickChatMember([
                'chat_id' => $chat_id,
                'user_id' => $bot->getId(),
            ]);
        }
        return $x;
    }
}
