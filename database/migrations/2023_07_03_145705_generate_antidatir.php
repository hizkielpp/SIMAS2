<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
CREATE PROCEDURE `generateAntidatir`()
        BEGIN
 DECLARE x,max  INT;
 DECLARE str  VARCHAR(255);
    DECLARE now,start,end DATE;
        
 SET x = 1;
 SET str =  '';
    SET now = DATE(NOW());
    SELECT DATE_ADD(DATE_ADD(LAST_DAY(NOW()), INTERVAL 1 DAY), INTERVAL -1 MONTH) INTO start;
    SELECT LAST_DAY(now()) INTO end;
    SELECT IFNULL(MAX(nomorSurat),0)  into max FROM suratkeluar WHERE tanggalPengesahan >= start AND tanggalPengesahan <= end;
    
    IF DAYNAME(now) <> 'Saturday' AND DAYNAME(now) <> 'Sunday' THEN
    SET FOREIGN_KEY_CHECKS=0;
 loop_label:  LOOP
  IF  x > 20 THEN
   LEAVE  loop_label;
  END  IF;
        SET max = max +1;
        SET  x = x + 1;
        SELECT max;
         insert into
suratkeluar(
    `tanggalPengesahan`,
    `created_at`,
    `updated_at`,
    `nomorSurat`,
    `kodeHal`,
    `sifatSurat`,
    `jenis`,
    `status`,
    `tujuanSurat`,
    `lampiran`,
    `jumlahLampiran`,
    `created_by`,
    `kodeUnit`,
    `disahkanOleh`
    ) values (
        now,
        now,
        now,
        max,
        'x',
        'rahasia',
        'antidatir',
        'belum',
        'x',
        lampiran,
        1,
        1,
        'x',
        'x'
    );
 END LOOP;
    SET FOREIGN_KEY_CHECKS=1;
    END IF;
END");

        DB::statement("
CREATE EVENT `generateAntidatir` ON SCHEDULE EVERY 1 DAY STARTS '2023-09-01 23:59:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL generateAntidatir()
        ");
        DB::statement('SET GLOBAL event_scheduler = ON;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS `generateAntidatir`');
        DB::statement('DROP EVENT generateAntidatir;');
    }
};
