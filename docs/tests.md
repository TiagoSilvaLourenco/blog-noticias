 ## ✅ Executando os Testes do Projeto

Este projeto utiliza o **Pest PHP** para organização e execução dos testes automatizados. Abaixo estão instruções práticas para rodar os testes conforme as **features** existentes.

---

### 🔧 Requisitos

Antes de executar os testes, certifique-se de:

* Ter o ambiente `.env.testing` configurado corretamente.
* Estar com o banco de dados limpo para o ambiente de testes.
* Ter executado `composer install` com todas as dependências instaladas.

---

### 🔄 Rodar todos os testes

```bash
php artisan test
# ou, se estiver com Pest instalado globalmente:
pest
```

---

### 🧪 Rodar apenas os testes que falharam anteriormente

```bash
php artisan test --only-failures
# ou
pest --only-failures
```

---

### ⏱️ Rodar os testes mais lentos por último (pós-falha ou durante dev)

```bash
php artisan test --slow
# ou
pest --slow
```

---

## 🎯 Rodando Testes por Feature

---

### 👤 Testes de Usuário

Testes relacionados à criação, edição, senhas e permissões de usuários.

```bash
php artisan test tests/Feature/Auth/UserTest.php
```

Ou rodar todos da pasta de autenticação:

```bash
php artisan test tests/Feature/Auth
```

---

### 📰 Testes de Post (posts, agendamentos e rascunhos)

```bash
php artisan test tests/Feature/PostTest.php
```

---

### 🍿 Testes de Tags

```bash
php artisan test --filter="Tags Feature"
# ou
php artisan test tests/Feature/TagTest.php
```

---

### 📂 Testes de Categorias

```bash
php artisan test --filter="Categories Feature"
# ou
php artisan test tests/Feature/CategoryTest.php
```

---

### 📢 Testes de Propagandas (Ads)

```bash
php artisan test --filter="Ads Feature"
# ou
php artisan test tests/Feature/AdsTest.php
```

---

### 🥪 Rodar teste individual por descrição

Se quiser rodar um único teste, use parte da descrição:

```bash
php artisan test --filter="admin and editor can create tag"
```

---

### 🔄 Resetar o status dos testes que falharam

Após corrigir erros, para limpar o cache de falhas anteriores:

```bash
php artisan test --clear-failures
```
