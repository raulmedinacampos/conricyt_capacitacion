<?php
class SessionManager {

    var $life_time;

    function SessionManager() {
        $this->life_time = get_cfg_var("session.gc_maxlifetime");
        session_set_save_handler(
                array(&$this, "open"), array(&$this, "close"), array(&$this, "read"), array(&$this, "write"), array(&$this, "destroy"), array(&$this, "gc")
        );
    }

    function open($save_path, $session_name) {
        global $sess_save_path;
        $sess_save_path = $save_path;
        return true;
    }

    function close() {
        return true;
    }

    function read($id) {
        $dbh = Sdx_ConectaBase();
        $data = '';
        $time = time();

        $stmt = mysqli_stmt_init($dbh);
        mysqli_stmt_prepare($stmt, 'SELECT session_data FROM sessions WHERE session_id=? AND expires > ?');
        mysqli_stmt_bind_param($stmt, 'si', $id, $time);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $data);
        mysqli_stmt_store_result($stmt);
        $nr = mysqli_stmt_num_rows($stmt);

        if ($nr > 0) {
            mysqli_stmt_fetch($stmt);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbh);
        return $data;
    }

    function write($id, $data) {
        $dbh = Sdx_ConectaBase();
        $time = time() + $this->life_time;

        $stmt = mysqli_stmt_init($dbh);
        mysqli_stmt_prepare($stmt, 'REPLACE sessions (session_id,session_data,expires) VALUES (?,?,?)');
        mysqli_stmt_bind_param($stmt, 'ssi', $id, $data, $time);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbh);

        return true;
    }

    function destroy($id) {
        $dbh = Sdx_ConectaBase();

        $stmt = mysqli_stmt_init($dbh);
        mysqli_stmt_prepare($stmt, 'DELETE FROM Sessions WHERE session_id=?');
        mysqli_stmt_bind_param($stmt, 's', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbh);

        return true;
    }

    function gc() {
        $dbh = Sdx_ConectaBase();
        $stmt = mysqli_stmt_init($dbh);
        mysqli_stmt_prepare($stmt, 'DELETE FROM Sessions WHERE expires < UNIX_TIMESTAMP()');
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbh);

        return true;
    }

}

?>
