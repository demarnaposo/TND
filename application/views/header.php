<!-- START X-NAVIGATION VERTICAL -->
<ul class="x-navigation x-navigation-horizontal x-navigation-panel">

    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button">
        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
    </li>
    <!-- END TOGGLE NAVIGATION -->

    <!-- SEARCH -->
    <li class="plugin">
            <div class="plugin-clock">00:00</div>
    </li>
    <li class="plugin">
            <b> || </b>
    </li>
    <li class="plugin">
            <div class="plugin-date">Loading...</div>
    </li>   
    <!-- END SEARCH -->

    <!-- SIGN OUT -->
    <li class="xn-icon-button pull-right">
        <a href="#" class="mb-control" data-box="#mb-signout" data-toggle="tooltip" data-placement="bottom" title="Keluar"><span class="fa fa-sign-out"></span></a>                        
    </li> 
    <!-- END SIGN OUT -->

    <!-- MESSAGES -->
    <li class="xn-icon-button pull-right">
        <a href="#" data-toggle="tooltip" data-placement="bottom" title="Pesan"><span class="fa fa-envelope-o"></span></a>
        <div class="informer" style="background-color: #d67011"><?php echo messages(); ?></div>
        <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-envelope"></span> Pesan</h3>                                
                <div class="pull-right">
                    <span class="label" style="background-color: #d67011"><?php echo messages(); ?> baru</span>
                </div>
            </div>
            <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                <?php if (messages_draft() >= 1) { ?>
                <a href="<?php echo site_url('suratkeluar/draft') ?>" class="list-group-item">
                    <span class="fa fa-envelope-o"></span>
                    <span class="contacts-title">Draft Surat</span>
                    <p>Terdapat <span class="label" style="background-color: #d67011;"><?php echo messages_draft(); ?> baru</span> surat yang masih dalam proses draft</p>
                </a>
                <?php } ?>
                <?php if (messages_ttd() >= 1) { ?>
                <a href="<?php echo site_url('suratkeluar/draft/signature') ?>" class="list-group-item">
                    <span class="fa fa-envelope-o"></span>
                    <span class="contacts-title">Tanda Tangani Surat</span>
                    <p>Terdapat <span class="label" style="background-color: #d67011;"><?php echo messages_ttd(); ?> baru</span> surat yang harus ditandatangni</p>
                </a>
                <?php } ?>
                <?php if (messages_suratmasuk() >= 1) { ?>
                <a href="<?php echo site_url('suratmasuk/inbox') ?>" class="list-group-item">
                    <span class="fa fa-envelope-o"></span>
                    <span class="contacts-title">Surat Masuk</span>
                    <p>Terdapat <span class="label" style="background-color: #d67011;"><?php echo messages_suratmasuk(); ?> baru</span> surat masuk</p>
                </a>
                <?php } ?>
            </div>
        </div>                        
    </li>
    <!-- END MESSAGES -->

    <!-- PROFILE -->
    <li class="xn-icon-button pull-right">
        <a href="<?php echo site_url('home/profil') ?>" data-toggle="tooltip" data-placement="bottom" title="Profil"><span class="fa fa-user"></span></a>                        
    </li> 
    <!-- END PROFILE -->

</ul>
<!-- END X-NAVIGATION VERTICAL -->
