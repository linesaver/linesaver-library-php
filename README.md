# afilaanda-library-php
A PHP Library for A Fila Anda

# Instalação

```php
require('./library.php');

$accessToken = $_GET['accessTokenQueue'];
// SALVAR ACCESS TOKEN (pode ser na session ou em cookies);

// EXECUTE ESSE SCRIPT ANTES DE QUALQUER SCRIPT QUE NECESSITE CONEXÃO NO BANCO DE DADOS
checkAccessToken('61780945263ad50009f2e990', '{{ACCESS_TOKEN}}');
```
