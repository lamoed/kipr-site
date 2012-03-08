<?php

class Student extends MainController
{
    
    public function Head() {}
    
    public function First() {
        $this->forward($this, 'information');
    }
    
    public function information() {
        $this->showPage('student');
    }
    
    private function showPage($name) {
        $res = $this->mdl()->dbgetPage($name);
        if(empty($res)) $this->redirect('/');
        $this->view->setTitle($res->header);
        $this->view->page = $res;
        $this->view->render('main/page');
    }

    public function documents($folder = 'student') {
        $this->view->setTitle($this->getTitle());
        $fld = $this->mdl()->folderCheck($folder);
        $this->view->docs     = $this->mdl()->documentsGet($fld);
        $this->view->sections = $this->mdl()->currentSectionsGet($fld);
        $current_fld = $this->mdl()->folderInfo($fld);
        if($current_fld) {
            $this->view->setTitle($this->getTitle() . ' - ' . $current_fld->name);
            $this->view->docs_name = $current_fld->name;
            $this->view->prev = $this->mdl()->prevSectionsGet($fld, $current_fld->filename, $current_fld->parent);
        }
        $this->view->prevmain = 'student';
        if($fld == 'student') $this->view->first = true;
        $this->view->render('main/docs');
    }
}

?>