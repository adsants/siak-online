@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-5">

                <font size="2" face="Calibri" >

                <table width="100%">

                    <tr>
                        <td width="15%">
                            <image src="{{ asset('/images/logo.png') }}" height="80px"/>
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

                                        {{$row->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Umur
                                    </td>
                                    <td>
                                        :
                                        &nbsp;

                                        {{$row->umur}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pekerjaan
                                    </td>
                                    <td>
                                        :
                                        &nbsp;

                                        {{$row->pekerjaan}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Alamat
                                    </td>
                                    <td>
                                        :
                                        &nbsp;

                                        {{$row->alamat}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        Telah menjalani pemeriksaan psikologi dan dinyatakan
                        <?php
                            if($lulus){
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
                                if($lulus){
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
                                    Trigasi Ayu Carina. Y, S.Psi
                                    </td>
                                </tr>


                            </table>
                        </td>
                    </tr>
                </table>
                </font>
            </div>
        </div>
    </div>
</div>
@endsection
