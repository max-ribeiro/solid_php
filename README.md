# Single Responsibility Principle (SRP)
A module should be responsible to one, and only one, actor.

# Caso de uso do mundo real
## Serviço de processamento de pedidos
- Processador de pedidos recebe o id do produto e o metódo de pagamento do usuario
- Coleta o produto do deposito
- Coleta o nivel de estoque do produto no deposito
- Se o produto possui o estoque, aplica o desconto, caso contrario dispara um erro
- Tenta validar o pagamento através do método de pagamento especificado pelo usuario
- Uma vez que o pagamento foi efetivado com sucesso, retorna uma mensagem
- Faz a atualização do numero de estoque do produto e retorna um array de mensagens
