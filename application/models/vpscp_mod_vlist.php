<?php
	class Vpscp_mod_vlist extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
   	public function show_vlist() {
      // check level to see if we are reseller or admin
      $query = $this->db->query('SELECT * FROM `users` WHERE `username`="'.$this->session->userdata('user').'"');
      foreach($query->result() as $row) {
        if($row->level=='reseller') {
          $level = 'reseller';
        } elseif($row->level=='admin') {
          $level = 'admin';
        } else {
          $level = 'customer';
        }
      }
      if($level=='reseller') {
        echo '<button class="btn btn-info" onclick="loadreselleroptions()">Create Virtual Server</button><br><br>';
      } elseif($level=='admin') {
        echo '<button class="btn btn-info" onclick="loadadminoptions()">Administrator</button><br><br>';
      }
   		$u = $this->session->userdata('user');
   		$q = $this->db->query('SELECT * FROM `virtual-servers` WHERE `owner`="'.$u.'"');
   		?><table class="table table-striped table-bordered">
          <thead>
            <th><img src="<?=base_url()?>templates/img/arrow_refresh.png" border="0"></th>
            <th>VT</th>
            <th>Hostname</th>
            <th>IP Address</th>
            <th>Operating System</th>
            <th>Memory</th>
            <th>Disk</th>
            <th>Bandwidth</th>
            <th>&nbsp;</th>
          </thead><tbody><?php
   		foreach($q->result() as $row) {
   			$id = $row->id;
   			if($row->status=='1') {$img='online';}else{$img='offline';}
   			if($row->vz=='ovz'){$vz='vz.gif';}else{$vz='kvm.png';}
   			$hostname = $row->hostname;
   			$ip = $row->ip_main;
   			$os = $row->os_friendly;
   			$memory = $row->memory;
   			$disk = $row->disk;
   			$bandwidth = $row->bandwidth;
   			?>
            <tr>
              <td><img src="<?=base_url()?>templates/img/<?=$img?>.png" border="0"></td>
              <td><img src="<?=base_url()?>templates/img/<?=$vz?>" border="0"></td>
              <td><a href="#"><?=$hostname?></a></td>
              <td><?=$ip?></td>
              <td><?=$os?></td>
              <td><?=$memory?> MB</td>
              <td><?=$disk?> GB</td>
              <td><?=$bandwidth?> GB</td>
              <td><a id="manage-btn" onclick="clicked_manage(<?=$id?>);" class="btn btn-small btn-success">Manage</a></td>
            </tr><?php
   		}
		?></tbody>
	</table>
        <?php
	}
  function pages() {
    $pages = array('settings');
    if(in_array($this->input->post('page'),$pages)) {
      // DEBUG: echo $this->input->post('page').' is in array';
      $page = $this->input->post('page');
      if($page == 'settings') {
        ?>
              Username <input type='text' name='user' value='' placeholder='<?=$this->session->userdata('user')?>'><br>
              Password: <input type='password' name='pass' value='' placeholder=''><br>
              <button onclick="alert('disabled in demo mode')" class="btn btn-info">Update</button>
        <?php
      }
    }
  }
  function vserver() {
    if($this->session->userdata('vpscp')) {
      $id = $this->input->post('vserverid');
      $q= $this->db->query('SELECT * FROM `virtual-servers` WHERE `id`="'.$id.'" AND `owner`="'.$this->session->userdata('user').'"');
      if($q->num_rows()>=1) {
        foreach($q->result() as $r) {
          if($r->status=='1') {
            $status = 'Online';
            $status_color = 'green';
          } else {
            $status = 'Offline';
            $status_color = 'red';
          }
          $ip = $r->ip_main;
          if($r->ipv6=='') {
            $ipv6_count = 0;
          } else {
            $ipv6_count = $r->ipv6;
          }
          // get node name
          $n_id = $r->node;
          $q2 = $this->db->query('SELECT * FROM `nodes` WHERE `id`="'.$n_id.'"');
          foreach($q2->result() as $r2) {
            $node = $r2->name;
          }
          $disk = $r->disk;
          $memory = $r->memory;
          $bandwidth = $r->bandwidth;
          if($r->vz=='ovz') {
            $vz = 'vz.gif';
          } else {
            $vz = 'kvm.png';
          }
          $os = $r->os_friendly;
          $hostname = $r->hostname;
        }
        ?> <script>var vsid='<?=$id?>'; $("#brand-head").click(function(e){ e.preventDefault(); window.location="<?=base_url()?>site/main";});</script>
          <!-- tab loading -->
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#home">Information</a></li>
            <li><a href="#power">Power Options</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="home">
              <table class="table table-striped table-bordered" width="50%">
                <thead></thead>
                <tbody>
                  <tr><td><strong>Status</strong></td> <td><font color='<?=$status_color?>'><?=$status?></font></td></tr>
                  <tr><td><strong>IP Address</strong></td> <td><?=$ip?></td></tr>
                  <tr><td><strong>IPV6 Address</strong></td> <td><?=$ipv6_count?></td></tr>
                  <tr><td><strong>Node</strong></td> <td><?=$node?></td></tr>
                  <tr><td><strong>Disk Space</strong></td> <td><?=$disk?> GB</td></tr>
                  <tr><td><strong>Memory</strong></td> <td><?=$memory?> MB</td></tr>
                  <tr><td><strong>Bandwidth</strong></td> <td><?=$bandwidth?> GB</td></tr>
                  <tr><td><strong>Virtualization Type</strong></td> <td><img src="<?=base_url()?>templates/img/<?=$vz?>"></td></tr>
                  <tr><td><strong>Operating System</strong></td> <td><?=$os?></td></tr>
                  <tr><td><strong>Hostname</strong></td> <td><?=$hostname?></td></tr>
                </tbody>
              </table>
            </div>
            <div class="tab-pane" id="power"><button class="btn btn-success">Boot</button> <button class="btn btn-info">Reboot</button> <button class="btn btn-danger">Shutdown</button></div>
          </div>
          <script>
            $("#myTab a").click(function (e) {
              e.preventDefault();
              $(this).tab('show');
            });
          </script><br><br>
          <ul class="nav nav-tabs" id="myTabz">
            <li class="active"><a href="#general">General</a></li>
            <li><a href="#hostname">Hostname</a></li>
            <li><a href="#operatingsystem">Operating System</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="general">
              You can manage additional settings in the following tabs.
            </div>
            <div class="tab-pane" id="hostname">
                <input type='text' id="hostname-input" name='hostname' placeholder='host.name'> <button class="btn btn-success" onclick="updatehostname()">Update Hostname</button>
            </div>
            <div class="tab-pane" id="operatingsystem">
                <button onclick="loadreinstalldialog()" class="btn btn-info">Reinstall</button>
            </div>
          <script>
            $("#myTabz a").click(function (e) {
              e.preventDefault();
              $(this).tab('show');
            });
            function loadreinstalldialog() {
              $("#myReinstallDialog").modal('show');
            }
            function reinstall(media_id) {
              // Send command to machine
            }
          </script>
            <!-- Modal -->
            <div id="myReinstallDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myReinstallDialog" aria-hidden="true">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Reinstall</h3>
              </div>
              <div class="modal-body">
                <p>Please selet a operating system below to reinstall your virtual server to.<br>
                <?php 
                  $q = $this->db->query('SELECT * FROM `media`');
                  foreach($q->result() as $r) {
                    echo '<img src="'.base_url().'templates/img/icons/'.$r->img.'"> <button class="btn btn-danger" onclick="reinstall('.$r->id.')">Reinstall to '.$r->name.'</button><br>';
                  }
                ?>
                </p>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
              </div>
            </div>
          <script>
            function updatehostname() {
              var newhost = $("#hostname-input").val();
              // Submit changes to database
              $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Updating hostname....</div>');
              $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/vserver_update",
                        data: {"what": "hostname", "id": vsid, "additional": newhost},
                        cache: false,
                        success: function(output) {
                            //  alert(output.success);
                            if(output!='error') {
                              $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Updated hostname</div>');
                              // Live updating
                              setTimeout(function(){clicked_manage(vsid)},2500);
                            } else {
                              $("#msgs").html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Failed to update hostname</div>');
                            }
                            if(output=='security_error') {
                                security_error();
                            }
                        }
                        })
            }
            function bootserver() {
              $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Booting...</div>');
              $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/vserver_update",
                        data: {"what": "boot", "id": vsid, "additional": ""},
                        cache: false,
                        success: function(output) {
                            //  alert(output.success);
                            if(output!='error') {
                              $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Booted</div>');
                              // Live updating
                              setTimeout(function(){clicked_manage(vsid)},2500);
                            } else {
                              $("#msgs").html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Failed to boot server</div>');
                            }
                        }
                        })
            }
            function rebootserver() {
              $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Rebooting...</div>');
              $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/vserver_update",
                        data: {"what": "reboot", "id": vsid, "additional": ""},
                        cache: false,
                        success: function(output) {
                            //  alert(output.success);
                            if(output!='error') {
                              $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Rebooted</div>');
                              // Live updating
                              setTimeout(function(){clicked_manage(vsid)},2500);
                            } else {
                              $("#msgs").html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Failed to reboot server</div>');
                            }
                        }
                        })
            }
            function shutdownserver() {
              $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Powering off...</div>');
              $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/vserver_update",
                        data: {"what": "boot", "id": vsid, "additional": ""},
                        cache: false,
                        success: function(output) {
                            //  alert(output.success);
                            if(output!='error') {
                              $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Powered off.</div>');
                              // Live updating
                              setTimeout(function(){clicked_manage(vsid)},2500);
                            } else {
                              $("#msgs").html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Failed to power off server.</div>');
                            }
                        }
                        })
            }
          </script>
        <?php
      } else {
        echo 'error';
      }
    } else {
      redirect('site/login');
    }
  }
  function vupdate() {
    $what = $this->input->post('what');
    $id = mysql_real_escape_string($this->input->post('id'));
    if(strpos($this->input->post('additional'), "'") !== false) {
      echo 'security_error';
      return false;
    }
    $add = mysql_real_escape_string($this->input->post('additional'));
    // check ownership
    $q= $this->db->query('SELECT * FROM `virtual-servers` WHERE `id`="'.$id.'" AND `owner`="'.$this->session->userdata('user').'"');
    if($q->num_rows() >= 1) {
      // lets update database since we're in development mode atm
      $this->db->query('UPDATE `virtual-servers` SET `hostname`="'.$add.'" WHERE `id`="'.$id.'"');
    } else {
      echo 'error';
    }
    if($what=='boot') {
      /* VSERVER BOOTING */
      $this->db->query('UPDATE `virtual-servers` SET `status`="1" WHERE `id`="'.$id.'"');
      // RUN BOOT
    }
    if($what=='reboot') {
      /* VSERVER REBOOTING */
      $this->db->query('UPDATE `virtual-servers` SET `status`="2" WHERE `id`="'.$id.'"');
      // RUN REBOOT

      // UPDATE STATUS
      $this->db->query('UPDATE `virtual-servers` SET `status`="1" WHERE `id`="'.$id.'"');
    }
    if($what=='shutdown') {
      /* VSERVER SHUTDOWN */
      $this->db->query('UPDATE `virtual-servers` SET `status`="2" WHERE `id`="'.$id.'"');
      // RUN SHUTDOWN
    }
  }
  function security_trigger() {
    echo '<center><h1>Error</h1><div style="border:1px solid;">An action you have taken has triggered a security rule. If you feel this is an error, please contact a site admin.</div></center>';
  }
  function check_level() {
    $query = $this->db->query('SELECT * FROM `users` WHERE `username`="'.$this->session->userdata('user').'"');
    foreach($query->result() as $row) {
     if($row->level=='admin') {
        ?>
        function loadadminoptions() {
            $.ajax({
              type: "POST",
                url: "<?php echo base_url(); ?>site/loadadminoptions",
                data: {"s": "s"},
                cache: false,
                success: function(output) {
                    if(output!='error') {
                      $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Welcome Admin <?=$this->session->userdata('user')?></div>');
                      $("#main").html(output);
                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Error loading administrator panel.</div>');
                    }
                }
            });
        }
        <?php
       }
     }
  }
    function adminz() {
      $query = $this->db->query('SELECT * FROM `users` WHERE `username`="'.$this->session->userdata('user').'"');
      foreach($query->result() as $row) {
      if($row->level=='admin') {
        $can = 'yes';
      } else {
          $can = 'no';
      }
    }
    if($can=='yes') {
      // Render Admin Panel
        ?>
          <div class="well well-small">
              <button onclick="adminswitch('nodes')" class="btn btn-info">Nodes</button> <button onclick="adminswitch('ipaddr')" class="btn btn-info">IP Addresses</button> <button onclick="adminswitch('vservers')" class="btn btn-info">Virtual Machines</button> <button onclick="adminswitch('customers')" class="btn btn-info">Customers</button> <button onclick="adminswitch('media')" class="btn btn-info">Media</button> <button onclick="adminswitch('settings')" class="btn btn-info">Configuration</button> 
          </div><br><div id="adm_bar">

        </div>

          <script>function adminswitch(option) {
            $.ajax({
              type: "POST",
                url: "<?php echo base_url(); ?>site/switchadminoptions",
                data: {"option": option},
                cache: false,
                success: function(output) {
                    if(output!='error') {
                      $("#adm_bar").html(output);
                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Error loading administrator option.</div>');
                    }
                }
            });
          }
function loaddialog(dialog_name) {$("#"+dialog_name).modal('show');}
          </script>
        <?php
    }
  }
  function adminz_options() {
      $option = mysql_real_escape_string($this->input->post('option'));
      if($option=='nodes') {
        /* # Section: NODES # */

      } elseif($option=='ipaddr') {
        /* # Section: IP ADDR # */
      } elseif($option=='vservers') {
        /* # Section: VSERVERS # */
      } elseif($option=='customers') {
        /* # Section: CUSTOMERS # */
      } elseif($option=='media') {
        /* # Section: MEDIA # */
      } elseif($option=='settings') {
        /* # Section: SETTINGS # */
        ?>
        <script>function showcontent(which) {
          if(which=='slaves') {
            $("#showedcontent").html('<a class="btn btn-info" onclick="loaddialog(\'slaves\')">Manage Slave Servers</a>');
          }
          if(which=='mainconfig') {
            $("#showedcontent").html('<a class="btn btn-info" onclick="loaddialog(\'mainconfig\')">Manage Site Configuration</a>');
          }
          if(which=='staff') {
            $("#showedcontent").html('<a class="btn btn-info" onclick="loaddialog(\'staff\')">Staff</a>');
          }
        }</script>
          <h3>Settings</h3>
          <div class="well"><a onclick="showcontent('slaves')" class="btn btn-inverse">Slaves</a> <a onclick="showcontent('staff')" class="btn btn-inverse">Staff</a> <a onclick="showcontent('mainconfig')" class="btn btn-inverse">Site Configuration</a></div>

          <div id="showedcontent">

          </div>
          <!-- modals -->
          <div id="slaves" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Slaves" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Slave Servers</h3>
          </div>
          <div class="modal-body">
            <p><a onclick="actions_modal('addslave')" class="btn btn-inverse">Add Slave Server</a><br><?php /* Load slave servers */ 
            $q = $this->db->query('SELECT * FROM vz_slaves');
            echo $q->num_rows().' slave servers found.';
            foreach($q->result() as $x) {
              //echo $q->num_rows().' slave servers found.';
            }
            ?></p>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <div id="mainconfig" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="mainconfig" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Main Configuration</h3>
          </div>
          <div class="modal-body">
            <p><?php /* Load main configuration */ ?></p>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Save changes</button>
          </div>
        
          <!-- modal ends -->
        <?php
      }
  }
}