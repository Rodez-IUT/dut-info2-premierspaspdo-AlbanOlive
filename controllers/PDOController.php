<?php

namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;

class PDOController {

    public function defaultAction($pdo) {
        $lettre = HttpHelper::getParam('lettre') ?: '';
        $status = HttpHelper::getParam('status');
        $status_id = HttpHelper::getParam('status_id') ?: 2;
        $user_id = HttpHelper::getParam('user_id');
        $action = HttpHelper::getParam('action');

        if (isset($status)) {
			switch ($status) {
				case 'waitValid':
					$status_id = 1;
					break;
				case 'active':
					$status_id = 2;
					break;
				case 'waitDel':
					$status_id = 3;
					break;
			}			
		}
        
        $stmt = $pdo->prepare("SELECT U.id,U.username,U.email,S.name FROM users U
						   JOIN status S ON S.id = U.status_id 
						   WHERE U.username LIKE ? AND U.status_id = ? ORDER BY username");
		$stmt->execute([$lettre.'%',$status_id]);    

        $view = new View("dut-info2-premierspaspdo-AlbanOlive/views/all_users");
        $view->setVar('status_id',$status_id);
        $view->setVar('stmt',$stmt);
        return $view;
    }

    public function askDeletion($pdo) {
        $lettre = HttpHelper::getParam('lettre') ?: '';
        $status = HttpHelper::getParam('status');
        $status_id = HttpHelper::getParam('status_id') ?: 2;
        $user_id = HttpHelper::getParam('user_id');
        $action = HttpHelper::getParam('action');

        if (isset($status)) {
			switch ($status) {
				case 'waitValid':
					$status_id = 1;
					break;
				case 'active':
					$status_id = 2;
					break;
				case 'waitDel':
					$status_id = 3;
					break;
			}			
		}

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO action_log (action_date,action_name,user_id) VALUES (NOW(),?,?)");
            $stmt->execute([$action,$user_id]);
            $stmt = $pdo->prepare("UPDATE users SET status_id = ? WHERE id = ?");
            $stmt->execute([3,$user_id]);
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $stmt = $pdo->prepare("SELECT U.id,U.username,U.email,S.name FROM users U
						   JOIN status S ON S.id = U.status_id 
						   WHERE U.username LIKE ? AND U.status_id = ? ORDER BY username");
		$stmt->execute([$lettre.'%',$status_id]);

        $view = new View("dut-info2-premierspaspdo-AlbanOlive/views/all_users");
        $view->setVar('status_id',$status_id);
        $view->setVar('stmt',$stmt);
        return $view;
    }
}