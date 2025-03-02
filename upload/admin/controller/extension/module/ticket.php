<?php
class ControllerExtensionModuleTicket extends Controller {
    private $error = [];

    public function index(): void {
        $this->load->language('extension/module/ticket');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/ticket');

        $this->getList();
    }

    public function add(): void {
        $this->load->language('extension/module/ticket');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/ticket');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_ticket->addTicket($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function edit(): void {
        $this->load->language('extension/module/ticket');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/ticket');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_ticket->editTicket($this->request->get['ticket_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete(): void {
        $this->load->language('extension/module/ticket');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/ticket');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $ticket_id) {
                $this->model_extension_module_ticket->deleteTicket($ticket_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getList();
    }

    protected function getList(): void {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['add'] = $this->url->link('extension/module/ticket/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('extension/module/ticket/delete', 'user_token=' . $this->session->data['user_token'], true);

        $data['tickets'] = [];

        $results = $this->model_extension_module_ticket->getTickets();

        foreach ($results as $result) {
            $data['tickets'][] = [
                'ticket_id' => $result['ticket_id'],
                'name'      => $result['name'],
                'gender'    => $result['gender'],
                'inquiry'   => $result['inquiry'],
                'status'    => $result['status'],
                'edit'      => $this->url->link('extension/module/ticket/edit', 'user_token=' . $this->session->data['user_token'] . '&ticket_id=' . $result['ticket_id'], true)
            ];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/ticket_list', $data));
    }

    protected function getForm(): void {
        $data['text_form'] = !isset($this->request->get['ticket_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true)
        ];

        if (!isset($this->request->get['ticket_id'])) {
            $data['action'] = $this->url->link('extension/module/ticket/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/ticket/edit', 'user_token=' . $this->session->data['user_token'] . '&ticket_id=' . $this->request->get['ticket_id'], true);
        }

        $data['cancel'] = $this->url->link('extension/module/ticket', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['ticket_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $ticket_info = $this->model_extension_module_ticket->getTicket($this->request->get['ticket_id']);
        }

        $data['name'] = isset($this->request->post['name']) ? $this->request->post['name'] : ($ticket_info['name'] ?? '');
        $data['gender'] = isset($this->request->post['gender']) ? $this->request->post['gender'] : ($ticket_info['gender'] ?? '');
        $data['inquiry'] = isset($this->request->post['inquiry']) ? $this->request->post['inquiry'] : ($ticket_info['inquiry'] ?? '');
        $data['status'] = isset($this->request->post['status']) ? $this->request->post['status'] : ($ticket_info['status'] ?? 'new');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/ticket_form', $data));
    }

    protected function validateForm(): bool {
        if (empty($this->request->post['name'])) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete(): bool {
        // Add validation logic if needed
        return true;
    }
}