<?php

namespace App;

use App\Enums\NotificationType;

class UserNotificationPayload
{
    const APP_LOGO = 'https://admin.yoki.uz/images/yoki_logo.svg';

    private string $title = '';

    private string $body = '';

    private NotificationType $type;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function body(string $title): static
    {
        $this->body = $title;

        return $this;
    }

    public function success(): static
    {
        $this->type = NotificationType::SUCCESS;

        return $this;
    }

    public function error(): static
    {
        $this->type = NotificationType::ERROR;

        return $this;
    }

    public function info(): static
    {
        $this->type = NotificationType::INFO;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type->value
        ];
    }
}
