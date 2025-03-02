<?php
class ModelExtensionModuleTicket extends Model {
    public function addTicket(array $data): void {
        $this->db->query("INSERT INTO " . DB_PREFIX . "ticket SET 
            name = '" . $this->db->escape($data['name']) . "', 
            gender = '" . $this->db->escape($data['gender']) . "', 
            inquiry = '" . $this->db->escape($data['inquiry']) . "', 
            status = '" . $this->db->escape($data['status']) . "'");
    }

    public function editTicket(int $ticket_id, array $data): void {
        $this->db->query("UPDATE " . DB_PREFIX . "ticket SET 
            name = '" . $this->db->escape($data['name']) . "', 
            gender = '" . $this->db->escape($data['gender']) . "', 
            inquiry = '" . $this->db->escape($data['inquiry']) . "', 
            status = '" . $this->db->escape($data['status']) . "' 
            WHERE ticket_id = '" . (int)$ticket_id . "'");
    }

    public function deleteTicket(int $ticket_id): void {
        $this->db->query("DELETE FROM " . DB_PREFIX . "ticket WHERE ticket_id = '" . (int)$ticket_id . "'");
    }

    public function getTicket(int $ticket_id): array {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ticket WHERE ticket_id = '" . (int)$ticket_id . "'");
        return $query->row;
    }

    public function getTickets(): array {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ticket");
        return $query->rows;
    }
}