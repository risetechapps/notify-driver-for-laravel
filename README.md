
# Laravel Notify Driver

## 📌 Sobre o Projeto
O **Laravel Notify Driver** é um pacote para Laravel que facilita a integração com o serviço **Notify**, permitindo o envio simplificado de **e-mails**, **SMS** e notificações (caso suportadas).

## ✨ Funcionalidades
- 📧 Envio de **e-mails** sem burocracia
- 📱 Envio de **SMS** com facilidade
- 🔔 Suporte a **notificações personalizadas** via canal `notify`

---

## 🚀 Instalação

### 1️⃣ Requisitos

Certifique-se de que seu projeto atende aos seguintes requisitos:
- PHP >= 8.0  
- Laravel >= 10  
- Composer instalado

### 2️⃣ Instalação do Pacote

Execute o comando abaixo no terminal:

```bash
  composer require risetechapps/notify-driver-for-laravel
```

### 3️⃣ Configuração

Adicione as credenciais no arquivo `config/services.php`:

```php
return [

    'notify' => [
        'key' => env('NOTIFY_KEY', ''),
        'from_name' => env('NOTIFY_FROM_NAME', ''),
    ],

];
```

Adicione ao seu `.env`:

```
NOTIFY_KEY=seu_token_aqui
NOTIFY_FROM_NAME=NomeDoRemetente
```

---

## 📦 Exemplos de Uso

### ✉️ Enviando Notificação por E-mail

```php
// app/Notifications/TestNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use RiseTechApps\Notify\Contracts\MessageContract;
use RiseTechApps\Notify\Message\MessageNotify;

class TestNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['notify'];
    }

    public function toNotify($notifiable): MessageContract
    {
        return (new MessageNotify($notifiable))
            ->notifyEmail()
            ->subject('Test Notification')
            ->action('https://globo.com', 'Acessar Link')
            ->to('usuario@email.com', 'Nome do Usuário')
            ->line('Esta é uma notificação de teste.')
            ->subjectMessage('Cabeçalho da Mensagem')
            ->theme('default')
            ->content([])
            ->send();
    }
}
```

**Disparando a notificação:**

```php
$user->notify(new \App\Notifications\TestNotification());
```

---

## 📦 Exemplos de Uso

### ✉️ Enviando Notificação por E-mail com anexos

```php
// app/Notifications/TestNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use RiseTechApps\Notify\Contracts\MessageContract;
use RiseTechApps\Notify\Message\MessageNotify;

class TestNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['notify'];
    }

    public function toNotify($notifiable): MessageContract
    {
        return (new MessageNotify($notifiable))
            ->notifyEmail()
            ->subject('Test Notification')
            ->action('https://globo.com', 'Acessar Link')
            ->to('usuario@email.com', 'Nome do Usuário')
            ->line('Esta é uma notificação de teste.')
            ->subjectMessage('Cabeçalho da Mensagem')
            ->theme('default')
            ->attachFromUrl($links)
            ->content([])
            ->send();
    }
}
```

**Disparando a notificação:**

```php
$user->notify(new \App\Notifications\TestNotification());
```

### 📲 Enviando Token por SMS

```php
// RiseTechApps/AuthService/Notifications/Token/TokenSecuritySMS.php

namespace RiseTechApps\AuthService\Notifications\Token;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use RiseTechApps\Notify\Contracts\MessageContract;
use RiseTechApps\Notify\Message\MessageNotify;

class TokenSecuritySMS extends Notification implements ShouldQueue
{
    use Queueable;

    protected string|int $token;

    public function __construct(string|int $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['notify'];
    }

    public function toNotify($notifiable): MessageContract
    {
        return (new MessageNotify($notifiable))
            ->notifySMS()
            ->setMessage(__('Use o código para validar sua autenticação: ' . $this->token))
            ->send();
    }
}
```

**Disparando a notificação:**

```php
$user->notify(new \RiseTechApps\AuthService\Notifications\Token\TokenSecuritySMS(123456));
```

---

## 🛠 Contribuição

Sinta-se à vontade para contribuir com este projeto:

1. Faça um fork do repositório  
2. Crie uma branch com sua funcionalidade: `feature/nome-da-funcionalidade`  
3. Faça commit das suas alterações  
4. Envie um Pull Request

---

## 📜 Licença

Este projeto é distribuído sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

💡 **Desenvolvido por [Rise Tech](https://risetech.com.br)**
