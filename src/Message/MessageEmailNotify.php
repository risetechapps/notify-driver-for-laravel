<?php

namespace RiseTechApps\Notify\Message;

class MessageEmailNotify
{
    protected $notifiable;
    public string|array $email;
    protected string $subject;
    protected array $content;
    protected array $attach = [];
    protected string $name;
    protected string $theme = 'default';
    protected ?string $line = null;
    protected array $lineHeader = [];
    protected array $lineFooter = [];
    protected array $action = [];
    private MessageNotify $messageNotify;
    protected ?string $subjectMessage = null;
    protected $signature = null;

    public function __construct($notifiable, MessageNotify $messageNotify)
    {
        $this->notifiable = $notifiable;
        $this->messageNotify = $messageNotify;

        $this->getEmail();
        $this->getName();
    }

    private function getEmail(): void
    {
        if (method_exists($this->notifiable, 'routeNotificationForEmail')) {
            $this->email = $this->notifiable->routeNotificationForEmail();
        }
    }

    private function getName(): void
    {
        if (method_exists($this->notifiable, 'routeNotificationForName')) {
            $this->name = $this->notifiable->routeNotificationForName();
        }
    }

    public function subject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function content(array $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function attachFromUrl(array|string $attach): static
    {
        if (gettype($attach) === 'string') {
            $this->attach[] = $attach;
        } elseif (gettype($attach) === 'array') {
            $this->attach = array_merge($this->attach, $attach);
        }
        return $this;
    }

    public function to(string $email, string $name = ''): static
    {
        $this->email = $email;
        $this->name = $name;

        return $this;
    }

    public function theme(string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function lineHeader(string $line): static
    {
        $this->lineHeader[] = $line;
        return $this;
    }

    public function lineFooter(string $line): static
    {
        $this->lineFooter[] = $line;
        return $this;
    }

    public function line(string $line): static
    {
        $this->line = $line;
        return $this;
    }

    public function action(string $url, string $text): static
    {
        $this->action['url'] = $url;
        $this->action['text'] = $text;

        return $this;
    }

    public function subjectMessage(string $subjectMessage): static
    {
        $this->subjectMessage = $subjectMessage;
        return $this;
    }

    public function setSignature(string $signature): static
    {
        if (is_null($this->signature)) {
            $this->signature = [];
        }

        $this->signature[] = $signature;
        return $this;
    }

    public function send(): MessageNotify
    {
        return $this->messageNotify;
    }

    public function toArray(): array
    {
        $data = array_merge($this->content ?? [], [
            'email' => $this->email ?? '',
            'name' => $this->name ?? '',
            'subject' => $this->subject ?? '',
            'theme' => $this->theme ?? 'default',
            'subject_message' => $this->subjectMessage ?? '',
            'app_name' => config('notify.from_name')
        ]);

        if (count($this->attach) > 0) {
            $data['attach'] = $this->attach;
        }

        if (!is_null($this->line)) {
            $data['line'] = $this->line;
        }

        if (count($this->lineHeader) > 0) {
            $data['line_header'] = $this->lineHeader;
        }

        if (count($this->lineFooter) > 0) {
            $data['line_footer'] = $this->lineFooter;
        }

        if (count($this->action) > 0) {
            $data['action'] = $this->action;
        }

        if (!is_null($this->signature) && count($this->signature) > 0) {
            $data['signature'] = $this->signature;
        }

        return $data;
    }
}
