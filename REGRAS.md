# 🧠 ORDER TRACKER PRO — Sistema de Rastreamento Logístico Premium

O **Order Tracker Pro** é uma plataforma de gerenciamento e monitoramento de entregas em tempo real. O sistema substitui a complexidade técnica de coordenadas geográficas tradicionais por uma experiência fluida baseada em endereços humanos, processando a automação de trajetos e a auditoria de estados nos bastidores.

---

## 🚦 Regras de Negócio Centrais (Business Rules)

### 1. 🌍 Geocodificação Automática de Endereços Urbanos
* **Regra:** O usuário final nunca digita latitudes ou longitudes. O sistema aceita strings de endereços convencionais (Ex: Rua/Av, Número, Cidade, Estado) tanto para o ponto de coleta quanto para o ponto de entrega.
* **Comportamento:** O backend intercepta os textos de endereço e faz uma conversão automática em coordenadas geográficas válidas antes de salvar o registro, garantindo a plotagem correta no mapa.

### 2. 🔒 Isolamento de Escopo e Privacidade (Multitenancy)
* **Regra:** Cada usuário autenticado tem acesso exclusivo e restrito aos seus próprios pacotes e envios.
* **Segurança:** O sistema bloqueia tentativas de espionagem de carga por manipulação de parâmetros (IDOR), garantindo confidencialidade absoluta dos dados de entrega.

### 3. 🛡️ Linha do Tempo e Imutabilidade (Auditoria Ledger)
* **Regra:** O histórico de movimentação do pacote é puramente cumulativo (Append-Only). É proibido editar, excluir ou retroceder eventos que já aconteceram na linha do tempo de rastreamento.
* **Comportamento:** Cada mudança de estado físico gera um carimbo de data/hora (*timestamp*) permanente para auditoria do cliente.

### 4. 🏁 Máquina de Estados de Logística
* **Regra:** O fluxo de transporte segue obrigatoriamente uma ordem cronológica que imita o mundo real, impedindo saltos de etapas impossíveis.
* **Fluxo Padrão:** `Preparando` ➡️ `Enviado` ➡️ `Em Trânsito` ➡️ `Saiu para Entrega` ➡️ `Entregue`.
* **Trava de Segurança:** Uma vez que o pacote atinge o estado final de `Entregue`, o registro é trancado, impedindo novos deslocamentos ou reabertura do trânsito.

### 🧮 Interpolação de Movimento (Vetor de Rota)
* **Regra:** Durante a simulação de progresso, o veículo de transporte deve orbitar estritamente o vetor linear calculado entre o Hub de Origem e o Destino do Cliente, impedindo desvios ou anomalias visuais no mapa.

---

## 🎛️ Arquitetura do Controlador (`DispatchController`)

Toda a engrenagem do sistema é controlada por métodos isolados e especializados que seguem rigorosamente o padrão arquitetural REST do ecossistema Laravel:

### 📑 Fluxos de Visualização (Métodos GET)

* **`index`**
  * **Responsabilidade:** Renderizar a Dashboard Principal do usuário. Carrega os indicadores consolidados de desempenho e a listagem de rastreamentos ativos.

* **`create`**
  * **Responsabilidade:** Apresentar a interface isolada do formulário de novos envios, focada em entradas de texto simplificadas e amigáveis (UX).

---

### ⚙️ Fluxos de Processamento e Ação (Método POST & APIs)

* **`store`**
  * **Responsabilidade:** Processar a criação e persistência do pedido. Valida os dados textuais recebidos, dispara o algoritmo de geração de código único e cria o primeiro registro de histórico (`Preparando`).

* **`updateStatus`**
  * **Responsabilidade:** Interceptar e validar mudanças na situação atual do pacote, verificando a elegibilidade da transição de status.

* **`simulateStep`**
  * **Responsabilidade:** Alimentar a coordenada de posição atual do veículo baseado na porcentagem de avanço da rota, disparando o gatilho visual de "Última Milha" quando o progresso se aproximar do destino.