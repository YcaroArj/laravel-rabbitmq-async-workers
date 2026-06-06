# Laravel + RabbitMQ

Este projeto foi desenvolvido com o objetivo de estudar a integração do Laravel com o RabbitMQ, explorando o processamento assíncrono de tarefas (Jobs) em um ambiente totalmente conteinerizado com Docker.

A arquitetura foi projetada para suportar alta carga de processamento, utilizando o **Supervisor** para gerenciar múltiplos workers em paralelo com travas automáticas de consumo de memória.

## 🚀 Tecnologias Utilizadas

- **PHP 8.4** (Imagem Alpine Customizada)
- **Laravel 11.x** (Modo CLI/Console)
- **RabbitMQ 3.13** (Com painel de gerenciamento Alpine)
- **Supervisor** (Monitor de processos Linux)
- **Docker & Docker Compose**

---

## 🛠️ Arquitetura e Estrutura de Workers

Para simular um ambiente de produção real, o ambiente Docker foi dividido em serviços distintos compartilhando a mesma rede:

1. **`rabbitmq`**: O broker de mensageria responsável por receber, armazenar e distribuir os Jobs.
2. **`app`**: Container web/cli responsável por disparar as mensagens (Produtor).
3. **`queue-worker`**: Um container dedicado exclusivamente para o consumo de mensagens. Ele roda o **Supervisor**, que mantém **5 Workers rodando em paralelo**.

### Gerenciamento de Memória (Anti-Leak)

Cada worker possui um limite rígido de consumo de memória de **128MB** (`--memory=128`). Caso um processo ultrapasse esse limite devido a vazamento de memória ou processamento pesado, ele é encerrado de forma limpa pelo Laravel e o Supervisor levanta uma nova instância imediatamente, garantindo estabilidade contínua.

---

## 💻 Como Rodar o Projeto

Graças à infraestrutura baseada em Docker, **não é necessário ter o PHP ou o Composer instalados na sua máquina física**. O build cuidará de todas as dependências automaticamente.
