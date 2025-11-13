<font size="2" face="Calibri" >

<table width="100%">

    <tr>
        <td width="15%">
            
            <img src="{{ public_path('images/logo.png')}}"  height="80px" alt="BTS">
        </td>
        <td width="85%">
            <h2 style="margin-top:;10px">
            BIRO KONSULTASI PSIKOLOGI SMARTPSI<br>
            (BPS) Wilayah Surabaya
            </h2>
        </td>
    </tr>
    <tr>
        <td width="15%">
        </td>
        <td width="85%" align="center">
            <hr>
                <h2 style="color:red"> 
                KETERANGAN HASIL TEST KESEHATAN ROHANI<br>
                UNTUK PEMOHON SIM
                </h2>
            <hr>
        </td>
    </tr>
</table>
    <table width="100%">
    <tr>
        <td width="55%">
        </td>
        <td width="45%" align="right">
            No. Reg :......................./ <span style="color:red"> Jawa Timur</span>
                
        </td>
    </tr>
    <tr>
        <td width="55%">
        Yang bertanda tangan di bawah ini menerangkan bahwa :
        </td>
        <td width="45%" align="right">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%">

                <tr>
                    <td width="20%">
                        Nama
                    </td>
                    <td width="80%">
                        :
                        &nbsp;
                        
                        {{$data['row']->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Umur
                    </td>
                    <td>
                        :
                        &nbsp;
                        
                        {{$data['row']->umur}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Pekerjaan
                    </td>
                    <td>
                        :
                        &nbsp;
                        
                        {{$data['row']->pekerjaan}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Alamat
                    </td>
                    <td>
                        :
                        &nbsp;
                        
                        {{$data['row']->alamat}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        Telah menjalani pemeriksaan psikologi dan dinyatakan 
        <?php
            if($data['lulus']){
                echo "Memenuhi Syarat / <s>Tidak Memenuhi Syarat</s>";
            }
            else{
                echo "<s>Memenuhi Syarat</s> / Tidak Memenuhi Syarat";
                
            }
        ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        untuk mendapatkan SIM:
        </td>
    </tr>
    <tr>
        <td colspan="2">
        Surat keterangan ini dibuat untuk dipergunakan seperlunya.
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php
                if($data['lulus']){
                    echo "<s>MENGULANG</s> / TIDAK MENGULANG";
                }
                else{
                    echo "MENGULANG / <s>TIDAK MENGULANG</s>";
                    
                }

            ?>
       
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <br>
        </td>
    </tr>
</table>

<table width="100%">

    <tr>
        <td width="70%">
        </td>
        <td width="30%">
            <table width="100%">

                <tr>
                    <td align="center">
                    Surabaya, <?php echo date('d-m-Y');?>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                   PSIKOLOG
                    </td>
                </tr>

                
                <tr>
                    <td align="center">
                   <br>
                    </td>
                </tr>

                
                <tr>
                    <td align="center">

                    <img src="data:image/png;base64, {!! $data['qrcode'] !!}">

                    </td>
                </tr>
                
                <tr>
                    <td align="center">
                   <br>
                    </td>
                </tr>

                <tr>
                    <td align="center">
                    Trigasi Ayu Carina. Y, S.Psi
                    </td>
                </tr>


            </table>
        </td>
    </tr>
</table>
</font>