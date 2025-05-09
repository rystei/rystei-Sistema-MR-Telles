# MR Telles  
**Plataforma web de gestão jurídica**  

---

## 🔖 Visão geral  
O **MR Telles** é um sistema desenvolvido como projeto de estágio em Engenharia de Software para um escritório de advocacia. Ele integra:  
- **Gestão Financeira** (parcelas, pagamentos via PIX)  
- **Gestão de Processos Jurídicos** (cadastro, acompanhamento, histórico)  
- **Agendamento de Consultas** (calendário interativo, seleção de horários)  
- **Gerenciamento de Eventos Internos** (FullCalendar para advogados)

---

## 🎯 Principais casos de uso  
1. **Processar Pagamento (Cliente)**
   - Visualizar parcelas mensais pendentes  
   - Gerar QR Code PIX  
   - Instruções de pagamento  
2. **Gerenciar Pagamento (Advogado)**
   - Cobranças avulsas via QR Code PIX  
   - Histórico de transações  
3. **Acompanhar Recursos Financeiros (Cliente)**
   - Listagem de parcelas (valor, vencimento, status)  
   - Transparência sobre obrigações financeiras  
4. **Gerenciar Recursos Financeiros (Advogado)**
   - Cadastro em lote de parcelas por cliente  
   - Atualização manual de status (pago)  
   - Exclusão de parcelas  
5. **Acompanhar Processo Jurídico (Cliente)**
   - Consulta de andamentos  
   - Visualização de histórico de etapas  
6. **Gerenciar Processo Jurídico (Advogado)**
   - Cadastro de novos processos  
   - Atualização de status e registro no histórico  
   - Exclusão de processos e seus históricos  
7. **Marcar Consulta (Cliente)**
   - Seleção de data/horário via calendário  
   - Listagem e exclusão de consultas  
8. **Gerenciar Eventos (Advogado)**
   - Criação, edição e exclusão de eventos em calendário  
   - Sincronização com agendamentos de consultas  

---

## 📐 Modelagem e diagramas  
- **Casos de Uso:** (https://drive.google.com/file/d/1fJRQKfvJV1F0zywzUJmpRe6xeypZTIiN/view?usp=drive_link)
- **Diagrama de Classes:** (https://drive.google.com/file/d/1TvwMG3uhVTwXJDk_lEIbVB6M1f3E_m96/view?usp=sharing)
- **DER (Entidade-Relacionamento):** (https://drive.google.com/file/d/1xy5V1LRPP-GTD2X9bM27JIYGuFXDHMW5/view?usp=sharing)`  
- **Diagrama de Implantação:** (https://drive.google.com/file/d/19f5FLzFoxXgUqvSh_Sjsb75gheyhy6zA/view?usp=sharing)
- **Diagrama de Sequência:**  
  - Gerenciar Eventos: (https://drive.google.com/file/d/1RcFLWaiU_-9YRoMu5vyfNuRcvZZTJn7e/view?usp=sharing)  
  - Marcar Consulta: (https://drive.google.com/file/d/1-iJCuEiQp0gN8fLllVs_s4hmJOikSqRH/view?usp=sharing) 
  - Gerenciar Pagamento: (https://drive.google.com/file/d/1BQfNU-FqOCFbsMW2H5OKJOyVUb7Y5woG/view?usp=sharing) 
  - Processar Pagamento: (https://drive.google.com/file/d/10ytOeJIJi3HA1-CcHewqLndzlEBCFRL7/view?usp=sharing)
  - Acompanhar Processo: (https://drive.google.com/file/d/1zpxpAP9xSUiykjnK7dm1hkgB2gW14AtB/view?usp=sharing) 
  - Gerenciar Processo: (https://drive.google.com/file/d/1IbA4KYw8kC4zp3d3I6DVXTlgl8E2q2Zg/view?usp=sharing)
  - Gerenciar Recursos Financeiros: (https://drive.google.com/file/d/126okwlEqqcFIzH1MuF2YpCqGJkhkcO3a/view?usp=sharing) 
  - Acompanhar Recursos Financeiros: (https://drive.google.com/file/d/1F6XVSbTKLmz5OoNXcg7iuEFB_h3w-lmJ/view?usp=sharing) 
- **Workflows BPMN:**  
  - AS-IS: (https://drive.google.com/file/d/1FvcHShtzcjopcV2KtSd4rhXCfEMNEfJO/view?usp=sharing) 
  - TO-BE: (https://drive.google.com/file/d/1twS7ocAwfi7VRJ7mLAEO57WaM7pkNzkI/view?usp=sharing)

---

## 🛠️ Tecnologias  
- **Backend:** PHP 8.2 + Laravel  
- **Banco de dados:** MySQL 8.0  
- **Frontend:** Blade + Bootstrap 5 + FullCalendar + Flatpickr  
- **ORM:** Eloquent  
- **QR Code:** BaconQrCode  
- **Controle de versão:** Git / GitHub  
