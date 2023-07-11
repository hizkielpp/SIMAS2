<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
    DECLARE now DATE;
        
	SET x = 1;
	SET str =  '';
    SET now = DATE(NOW());
    SELECT IFNULL(MAX(nomorSurat),0) into max FROM suratkeluar;
    
    SET FOREIGN_KEY_CHECKS=0; 
	loop_label:  LOOP
		IF  x > 20 THEN 
			LEAVE  loop_label;
		END  IF;
        SET max = max +1; 
        SET  x = x + 1;
        SELECT max;
         insert into 
suratkeluar(`tanggalPengesahan`,`created_at`,`updated_at`,`nomorSurat`,`kodeHal`,`sifatSurat`,`jenis`,`status`,`tujuanSurat`,`jumlahLampiran`) values (now,now,now,max,'x','rahasia','antidatir','belum','x',1);
	END LOOP;
    SET FOREIGN_KEY_CHECKS=1;
END

        ");
        DB::statement(("
 
CREATE EVENT `generateAntidatir` ON SCHEDULE EVERY 1 DAY STARTS '2023-01-18 23:30:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL generateAntidatir()
        "));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP PROCEDURE generateAntidatir;");
        DB::statement("DROP EVENT generateAntidatir;");
    }
};
