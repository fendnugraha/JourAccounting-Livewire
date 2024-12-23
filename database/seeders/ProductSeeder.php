<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ["code" => "HP00001", "name" => "TABLET ADVAN GALLILEA 3/16GB", "price" => "1500000", "cost" => "1300000", "category" => "handphone"],
            ["code" => "HP00002", "name" => "HP MAXTRON P12I BOMBA", "price" => "350000", "cost" => "290000", "category" => "handphone"],
            ["code" => "HP00003", "name" => "HP INFINIX HOT 11 PLAY 4/64GB", "price" => "1950000", "cost" => "1549000", "category" => "handphone"],
            ["code" => "HP00004", "name" => "HP INFINIX SMART 6 3/64GB", "price" => "1720000", "cost" => "1384000", "category" => "handphone"],
            ["code" => "HP00005", "name" => "HP INFINIX SMART 6 NFC 2/32GB", "price" => "1499000", "cost" => "1172000", "category" => "handphone"],
            ["code" => "HP00006", "name" => "HP EVERCOSS M6A 4/32GB", "price" => "1199000", "cost" => "900000", "category" => "handphone"],
            ["code" => "HP00007", "name" => "HP VIVO Y21T 6/128GB", "price" => "3099000", "cost" => "2790000", "category" => "handphone"],
            ["code" => "HP00008", "name" => "HP INFINIX HOT 11S NFC 6/128GB", "price" => "2399000", "cost" => "2232000", "category" => "handphone"],
            ["code" => "HP00009", "name" => "HP SAMSUNG GALAXY M12 4/64GB", "price" => "1999000", "cost" => "1859070", "category" => "handphone"],
            ["code" => "HP00010", "name" => "HP ALDO A007 BOND / BRANDCODE", "price" => "350000", "cost" => "225000", "category" => "handphone"],
            ["code" => "HP00011", "name" => "HP XIAOMI POCO M3 PRO 5G 6/128GB", "price" => "2999000", "cost" => "2800000", "category" => "handphone"],
            ["code" => "HP00012", "name" => "HP INFINIX NOTE 11 NFC 6/128GB", "price" => "2899000", "cost" => "2416000", "category" => "handphone"],
            ["code" => "HP00013", "name" => "HP SAMSUNG GALAXY S21 FE 5G 8/256GB", "price" => "9329070", "cost" => "9299070", "category" => "handphone"],
            ["code" => "HP00014", "name" => "HP SAMSUNG GALAXY A03 4/64GB", "price" => "1799000", "cost" => "1580070", "category" => "handphone"],
            ["code" => "HP00015", "name" => "HP SAMSUNG GALAXY A03 3/32GB", "price" => "1499000", "cost" => "1394070", "category" => "handphone"],
            ["code" => "HP00016", "name" => "HP VIVO V23 5G 8/128GB", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00017", "name" => "HP ALDO AL106", "price" => "200000", "cost" => "120000", "category" => "handphone"],
            ["code" => "HP00018", "name" => "HP OPPO A57 4/128GB", "price" => "2699000", "cost" => "2340000", "category" => "handphone"],
            ["code" => "HP00019", "name" => "HP ADVAN HAMMER R5 FLIP", "price" => "350000", "cost" => "270000", "category" => "handphone"],
            ["code" => "HP00020", "name" => "HP ALDO AF16", "price" => "250000", "cost" => "170000", "category" => "handphone"],
            ["code" => "HP00021", "name" => "HP VIVO Y75 5G 8/128GB", "price" => "3999000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00022", "name" => "HP VIVO Y75 8/128GB (DEMO)", "price" => "2999000", "cost" => "2400000", "category" => "handphone"],
            ["code" => "HP00023", "name" => "TAB ADVAN SKETSA 2", "price" => "2800000", "cost" => "2650000", "category" => "handphone"],
            ["code" => "HP00024", "name" => "HP OPPO A76 6/128GB", "price" => "3399000", "cost" => "3060000", "category" => "handphone"],
            ["code" => "HP00025", "name" => "HP VIVO Y15S 3/64GB", "price" => "1899000", "cost" => "1740000", "category" => "handphone"],
            ["code" => "HP00026", "name" => "HP VIVO Y21A 4/64(stok lama)", "price" => "2399000", "cost" => "2160000", "category" => "handphone"],
            ["code" => "HP00027", "name" => "TABLET EVERCOSS U70B 3/32GB", "price" => "1109000", "cost" => "850000", "category" => "handphone"],
            ["code" => "HP00028", "name" => "HP OPPO RENO 7Z 5G (DEMO)", "price" => "3299000", "cost" => "3000000", "category" => "handphone"],
            ["code" => "HP00029", "name" => "HP OPPO A55 4/64GB", "price" => "2699000", "cost" => "2430000", "category" => "handphone"],
            ["code" => "HP00030", "name" => "HP SAMSUNG GALAXY S22 ULTRA 12/512GB", "price" => "20999000", "cost" => "19529070", "category" => "handphone"],
            ["code" => "HP00031", "name" => "HP REALME 9 PRO 8/128GB", "price" => "3999000", "cost" => "3679000", "category" => "handphone"],
            ["code" => "HP00032", "name" => "HP REALME 9 PRO+ 8/256GB", "price" => "5499000", "cost" => "5059000", "category" => "handphone"],
            ["code" => "HP00033", "name" => "HP OPPO RENO 7Z 5G 8/128GB", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00034", "name" => "HP OPPO RENO 7 5G 8/256", "price" => "7499000", "cost" => "6750000", "category" => "handphone"],
            ["code" => "HP00035", "name" => "HP REALME C31 4/64GB", "price" => "1899000", "cost" => "1635000", "category" => "handphone"],
            ["code" => "HP00036", "name" => "HP EVERCOSS M6 3/16GB", "price" => "850000", "cost" => "777000", "category" => "handphone"],
            ["code" => "HP00037", "name" => "HP SAMSUNG A13 4/128GB", "price" => "2499000", "cost" => "2324070", "category" => "handphone"],
            ["code" => "HP00038", "name" => "HP SAMSUNG A13 6/128GB", "price" => "2699000", "cost" => "2510070", "category" => "handphone"],
            ["code" => "HP00039", "name" => "HP REALME C35 4/64GB", "price" => "2299000", "cost" => "1839000", "category" => "handphone"],
            ["code" => "HP00040", "name" => "HP REALME C35 4/128GB", "price" => "2499000", "cost" => "2324000", "category" => "handphone"],
            ["code" => "HP00041", "name" => "HP XIAOMI REDMI NOTE 11 6/128GB", "price" => "2899000", "cost" => "2522000", "category" => "handphone"],
            ["code" => "HP00042", "name" => "HP SAMSUNG A33 5G 8/128GB", "price" => "4999000", "cost" => "4463070", "category" => "handphone"],
            ["code" => "HP00043", "name" => "HP SAMSUNG A23 6/128", "price" => "3299000", "cost" => "3083070", "category" => "handphone"],
            ["code" => "HP00044", "name" => "HP SAMSUNG A53 5G 8/128GB", "price" => "5999000", "cost" => "5579070", "category" => "handphone"],
            ["code" => "HP00045", "name" => "HP OPPO RENO 7 8/256GB", "price" => "5199000", "cost" => "4680000", "category" => "handphone"],
            ["code" => "HP00046", "name" => "HP REALME C35 4/158 (DEMO)", "price" => "1299000", "cost" => "1199466", "category" => "handphone"],
            ["code" => "HP00047", "name" => "HP OPPO A96 8/256GB", "price" => "4099000", "cost" => "3690000", "category" => "handphone"],
            ["code" => "HP00048", "name" => "HP XIAOMI REDMI NOTE 11 PRO 8/128GB", "price" => "3899000", "cost" => "3640000", "category" => "handphone"],
            ["code" => "HP00049", "name" => "HP REALME C31 3/32GB", "price" => "1699000", "cost" => "1448000", "category" => "handphone"],
            ["code" => "HP00050", "name" => "HP VIVO Y33T 8/128GB", "price" => "3399000", "cost" => "3000000", "category" => "handphone"],
            ["code" => "HP00051", "name" => "HP VIVO Y01 2/32GB", "price" => "1399000", "cost" => "1314000", "category" => "handphone"],
            ["code" => "HP00052", "name" => "HP XIAOMI REDMI 10C 4/64GB", "price" => "1899000", "cost" => "1775000", "category" => "handphone"],
            ["code" => "HP00053", "name" => "HP REALME 9 8/128GB", "price" => "3599000", "cost" => "3128000", "category" => "handphone"],
            ["code" => "HP00054", "name" => "HP XIAOMI REDMI NOTE 10 PRO 8/128GB", "price" => "3699000", "cost" => "3455000", "category" => "handphone"],
            ["code" => "HP00055", "name" => "HP INFINIX HOT 11S NFC 4/64GB", "price" => "2222000", "cost" => "1954000", "category" => "handphone"],
            ["code" => "HP00056", "name" => "HP IPHONE 11 64GB", "price" => "8000000", "cost" => "7499000", "category" => "handphone"],
            ["code" => "HP00057", "name" => "HP ITEL IT2173", "price" => "230000", "cost" => "165000", "category" => "handphone"],
            ["code" => "HP00058", "name" => "HP SAMSUNG A03 4/128GB", "price" => "1999000", "cost" => "1673070", "category" => "handphone"],
            ["code" => "HP00059", "name" => "HP SAMSUNG A03S 3/32GB", "price" => "1599000", "cost" => "1394070", "category" => "handphone"],
            ["code" => "HP00060", "name" => "HP XIAOMI REDMI 10C 4/128", "price" => "2199000", "cost" => "1868000", "category" => "handphone"],
            ["code" => "HP00061", "name" => "HP INFINIX HOT 20I 4/64GB", "price" => "1699000", "cost" => "1406000", "category" => "handphone"],
            ["code" => "HP00062", "name" => "HP OPPO A16K 4/64GB", "price" => "2199000", "cost" => "1980000", "category" => "handphone"],
            ["code" => "HP00063", "name" => "HP OPPO A16K 4/64GB (DEMO)", "price" => "1500000", "cost" => "1100000", "category" => "handphone"],
            ["code" => "HP00064", "name" => "HP REALME 9I 6/128GB", "price" => "2999000", "cost" => "2759000", "category" => "handphone"],
            ["code" => "HP00065", "name" => "HP XIAOMI REDMI NOTE 10 5G 8/128GB", "price" => "2999000", "cost" => "2705000", "category" => "handphone"],
            ["code" => "HP00066", "name" => "SAMSUNG A53 5G 8/256GB", "price" => "6299000", "cost" => "5850070", "category" => "handphone"],
            ["code" => "HP00067", "name" => "HP ITEL A27 2/32GB", "price" => "1099000", "cost" => "855000", "category" => "handphone"],
            ["code" => "HP00068", "name" => "HP INFINIX HOT 12I 4/64GB", "price" => "1749000", "cost" => "1484000", "category" => "handphone"],
            ["code" => "HP00069", "name" => "HP ITEL IT5026", "price" => "399000", "cost" => "237000", "category" => "handphone"],
            ["code" => "HP00070", "name" => "HP NOKIA 106", "price" => "250000", "cost" => "170000", "category" => "handphone"],
            ["code" => "HP00071", "name" => "HP ADVAN NASA PRO 2/32", "price" => "1135000", "cost" => "965000", "category" => "handphone"],
            ["code" => "HP00072", "name" => "HP XIAOMI REDMI 10A 3/32GB", "price" => "1499000", "cost" => "1401000", "category" => "handphone"],
            ["code" => "HP00073", "name" => "HP POCO M4 PRO 8/256", "price" => "3499000", "cost" => "3270000", "category" => "handphone"],
            ["code" => "HP00074", "name" => "HP POCO M3 PRO 5G 4/64GB", "price" => "2599000", "cost" => "2335000", "category" => "handphone"],
            ["code" => "HP00075", "name" => "HP VIVO T1 5G 8/256GB", "price" => "4099000", "cost" => "3730000", "category" => "handphone"],
            ["code" => "HP00076", "name" => "HP SAMSUNG A73 5G 8/256GB", "price" => "7799000", "cost" => "7253070", "category" => "handphone"],
            ["code" => "HP00077", "name" => "HP ITEL A26 2/32GB", "price" => "900000", "cost" => "891000", "category" => "handphone"],
            ["code" => "HP00078", "name" => "HP ITEL A49 2/32GB", "price" => "1100000", "cost" => "943000", "category" => "handphone"],
            ["code" => "HP00079", "name" => "TABLET ADVAN A8 3/32GB", "price" => "1549000", "cost" => "1265000", "category" => "handphone"],
            ["code" => "HP00080", "name" => "HP REALME C30 2/32GB", "price" => "1399000", "cost" => "1121000", "category" => "handphone"],
            ["code" => "HP00081", "name" => "HP OPPO A57 4/64GB (DEMO)", "price" => "2400000", "cost" => "1200000", "category" => "handphone"],
            ["code" => "HP00082", "name" => "HP SAMSUNG A33 5G 8/256GB", "price" => "5299000", "cost" => "4928070", "category" => "handphone"],
            ["code" => "HP00083", "name" => "HP EVERCOSS M50", "price" => "830000", "cost" => "779000", "category" => "handphone"],
            ["code" => "HP00084", "name" => "HP OPPO A57 4/64GB", "price" => "2399000", "cost" => "2160000", "category" => "handphone"],
            ["code" => "HP00085", "name" => "HP VIVO X80 DEMO 12/256GB", "price" => "11999000", "cost" => "7200000", "category" => "handphone"],
            ["code" => "HP00086", "name" => "HP VIVO X80 12/256GB", "price" => "11999000", "cost" => "10919000", "category" => "handphone"],
            ["code" => "HP00087", "name" => "HP INFINIX HOT 12 PLAY 4/64GB", "price" => "1999000", "cost" => "1551000", "category" => "handphone"],
            ["code" => "HP00088", "name" => "HP REALME 9 PRO PLUS 8/128GB", "price" => "4999000", "cost" => "4599000", "category" => "handphone"],
            ["code" => "HP00089", "name" => "HP REALME C30 4/64GB", "price" => "1699000", "cost" => "1589000", "category" => "handphone"],
            ["code" => "HP00090", "name" => "HP OPPO RENO 8 5G 8/256GB (DEMO)", "price" => "5999000", "cost" => "4000000", "category" => "handphone"],
            ["code" => "HP00091", "name" => "HP OPPO RENO 8 4G 8/256GB (DEMO)", "price" => "2999000", "cost" => "2500000", "category" => "handphone"],
            ["code" => "HP00092", "name" => "HP VIVO Y35 8/128GB", "price" => "3399000", "cost" => "3000000", "category" => "handphone"],
            ["code" => "HP00093", "name" => "HP VIVO Y16 3/32GB DEMO", "price" => "1199000", "cost" => "1080000", "category" => "handphone"],
            ["code" => "HP00094", "name" => "HP EVERCOSS R70", "price" => "700000", "cost" => "625000", "category" => "handphone"],
            ["code" => "HP00095", "name" => "HP OPPO RENO 8 4G 8/256GB", "price" => "5199000", "cost" => "4500000", "category" => "handphone"],
            ["code" => "HP00096", "name" => "HP VIVO Y16 3/32GB", "price" => "1799000", "cost" => "1655000", "category" => "handphone"],
            ["code" => "HP00097", "name" => "HP REALME PAD MINI LTE 4/64GB", "price" => "2999000", "cost" => "2697000", "category" => "handphone"],
            ["code" => "HP00098", "name" => "HP POCO F4 8/256GB", "price" => "5699000", "cost" => "5325000", "category" => "handphone"],
            ["code" => "HP00099", "name" => "HP VIVO Y22 4/64GB", "price" => "2399000", "cost" => "2092000", "category" => "handphone"],
            ["code" => "HP00100", "name" => "HP POCO M4 PRO 6/128GB", "price" => "3200000", "cost" => "2825000", "category" => "handphone"],
            ["code" => "HP00101", "name" => "HP VIVO Y22 6/128GB", "price" => "2999000", "cost" => "2700000", "category" => "handphone"],
            ["code" => "HP00102", "name" => "HP VIVO V25 8/256GB (DEMO)", "price" => "3999000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00103", "name" => "HP XIAOMI REDMI NOTE 11 PRO 6/128GB", "price" => "3999000", "cost" => "3360000", "category" => "handphone"],
            ["code" => "HP00104", "name" => "HP SAMSUNG A04S 4/64GB", "price" => "1899000", "cost" => "1770000", "category" => "handphone"],
            ["code" => "HP00105", "name" => "HP ADVAN HAMMER R2", "price" => "220000", "cost" => "160000", "category" => "handphone"],
            ["code" => "HP00106", "name" => "HP VIVO V25E 8/128GB", "price" => "3999000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00107", "name" => "HP INFINIX NOTE 12 2023 8/258GB", "price" => "2999000", "cost" => "2704000", "category" => "handphone"],
            ["code" => "HP00108", "name" => "TAB SAMSUNG A7 LITE 3/32GB", "price" => "2499000", "cost" => "2324070", "category" => "handphone"],
            ["code" => "HP00109", "name" => "HP OPPO RENO 8 Z 5G 8/256GB (DEMO)", "price" => "3499000", "cost" => "3000000", "category" => "handphone"],
            ["code" => "HP00110", "name" => "HP OPPO RENO 8 5G 8/256GB", "price" => "7999000", "cost" => "7200000", "category" => "handphone"],
            ["code" => "HP00111", "name" => "HP OPPO RENO 8 Z 5G", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00112", "name" => "HP VIVO Y16 3/64GB", "price" => "1899000", "cost" => "1740000", "category" => "handphone"],
            ["code" => "HP00113", "name" => "HP SAMSUNG A04 4/64GB", "price" => "1799000", "cost" => "1490000", "category" => "handphone"],
            ["code" => "HP00114", "name" => "HP VIVO V23E 8/256GB", "price" => "4299000", "cost" => "3870000", "category" => "handphone"],
            ["code" => "HP00115", "name" => "HP SAMSUNG A23 5G 6/128GB", "price" => "3999000", "cost" => "3719070", "category" => "handphone"],
            ["code" => "HP00116", "name" => "HP OPPO A17 4/64 (DEMO)", "price" => "1499000", "cost" => "1050000", "category" => "handphone"],
            ["code" => "HP00117", "name" => "HP INFINIX SMART 6 PLUS 3/64GB", "price" => "1500000", "cost" => "1327000", "category" => "handphone"],
            ["code" => "HP00118", "name" => "HP XIAOMI REDMI A1 2/32GB", "price" => "1449000", "cost" => "1074000", "category" => "handphone"],
            ["code" => "HP00119", "name" => "HP XIAOMI REDMI A1 3/32GB", "price" => "1549000", "cost" => "1214000", "category" => "handphone"],
            ["code" => "HP00120", "name" => "HP VIVO V25E 12/256GB", "price" => "4799000", "cost" => "4320000", "category" => "handphone"],
            ["code" => "HP00121", "name" => "HP OPPO A17K 3/64GB", "price" => "1699000", "cost" => "1560000", "category" => "handphone"],
            ["code" => "HP00122", "name" => "HP SAMSUNG A04E 3/32GB", "price" => "1299000", "cost" => "1190000", "category" => "handphone"],
            ["code" => "HP00123", "name" => "HP VIVO Y02 DEMO", "price" => "1499000", "cost" => "900000", "category" => "handphone"],
            ["code" => "HP00124", "name" => "HP VIVO Y02 3/32GB", "price" => "1399000", "cost" => "1306000", "category" => "handphone"],
            ["code" => "HP00125", "name" => "HP POCO M5 4/64GB", "price" => "2299000", "cost" => "2055000", "category" => "handphone"],
            ["code" => "HP00126", "name" => "HP POCO M5 4/128GB", "price" => "2499000", "cost" => "2242000", "category" => "handphone"],
            ["code" => "HP00127", "name" => "HP VIVO V25E 8/256GB", "price" => "4299000", "cost" => "3870000", "category" => "handphone"],
            ["code" => "HP00128", "name" => "HP MITO 500", "price" => "270000", "cost" => "225000", "category" => "handphone"],
            ["code" => "HP00129", "name" => "HP VIVO V25 PRO 12/256GB", "price" => "8999000", "cost" => "8100000", "category" => "handphone"],
            ["code" => "HP00130", "name" => "HP OPPO RENO 8T 8/256GB", "price" => "4899000", "cost" => "4410000", "category" => "handphone"],
            ["code" => "HP00136", "name" => "HP REALME 10 PRO 8/256GB", "price" => "4999000", "cost" => "4499000", "category" => "handphone"],
            ["code" => "HP00148", "name" => "HP NOKIA 105 DS NEO", "price" => "320000", "cost" => "242000", "category" => "handphone"],
            ["code" => "HP00150", "name" => "HP REALME 10 PRO 8/128GB", "price" => "4599000", "cost" => "4140000", "category" => "handphone"],
            ["code" => "HP00151", "name" => "HP NOKIA 150", "price" => "465000", "cost" => "435000", "category" => "handphone"],
            ["code" => "HP00162", "name" => "HP REALME 10 PRO PLUS 8/128GB", "price" => "5999000", "cost" => "5399000", "category" => "handphone"],
            ["code" => "HP00187", "name" => "HP STRAWBERRY S1272", "price" => "208000", "cost" => "183400", "category" => "handphone"],
            ["code" => "HP00202", "name" => "HP VIVO Y16 4/32GB", "price" => "1699000", "cost" => "1584000", "category" => "handphone"],
            ["code" => "HP00203", "name" => "HP OPPO RENO 8T 8/256GB (DEMO)", "price" => "3499000", "cost" => "2880000", "category" => "handphone"],
            ["code" => "HP00229", "name" => "TAB SAMSUNG A7 LITE 3/32GB WIFI ONLY", "price" => "1999000", "cost" => "1859070", "category" => "handphone"],
            ["code" => "HP00244", "name" => "HP BRANDCODE B81 PRO LASER", "price" => "350000", "cost" => "285000", "category" => "handphone"],
            ["code" => "HP00253", "name" => "HP EVERCOSS U6 XTREAM ONE PLUS", "price" => "880000", "cost" => "600000", "category" => "handphone"],
            ["code" => "HP00254", "name" => "HP SAMSUNG A14 6/128GB", "price" => "2699000", "cost" => "2435000", "category" => "handphone"],
            ["code" => "HP00256", "name" => "HP REALME C30S 3/32GB", "price" => "1399000", "cost" => "1302000", "category" => "handphone"],
            ["code" => "HP00263", "name" => "HP EVERCOSS XTREAM ONE (S45)", "price" => "675000", "cost" => "500000", "category" => "handphone"],
            ["code" => "HP00266", "name" => "HP NOKIA C1 1/16GB", "price" => "900000", "cost" => "780000", "category" => "handphone"],
            ["code" => "HP00275", "name" => "HP REALME C30S 4/64GB", "price" => "1599000", "cost" => "1488000", "category" => "handphone"],
            ["code" => "HP00292", "name" => "HP INFINIX ZERO 20 8/256GB", "price" => "3699000", "cost" => "3337000", "category" => "handphone"],
            ["code" => "HP00310", "name" => "HP EVERCOSS N2E", "price" => "230000", "cost" => "170000", "category" => "handphone"],
            ["code" => "HP00318", "name" => "HP OPPO A74 6/128GB", "price" => "3499000", "cost" => "3060000", "category" => "handphone"],
            ["code" => "HP00332", "name" => "HP VIVO Y16 4/64GB", "price" => "1999000", "cost" => "1745000", "category" => "handphone"],
            ["code" => "HP00334", "name" => "HP OPPO RENO 8T 5G 8/128GB (DEMO)", "price" => "4499000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00338", "name" => "HP XIAOMI REDMI 10 5G 6/128GB", "price" => "2999000", "cost" => "2429000", "category" => "handphone"],
            ["code" => "HP00342", "name" => "HP OPPO A54 6/128GB", "price" => "3099000", "cost" => "2520000", "category" => "handphone"],
            ["code" => "HP00343", "name" => "HP OPPO RENO 8T 8/128GB", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00344", "name" => "HP REALME C55 6/128GB", "price" => "2499000", "cost" => "2299000", "category" => "handphone"],
            ["code" => "HP00345", "name" => "HP REALME C55 8/256GB", "price" => "2999000", "cost" => "2760000", "category" => "handphone"],
            ["code" => "HP00346", "name" => "HP OPPO A78 5G 8/128GB (DEMO)", "price" => "2899000", "cost" => "2400000", "category" => "handphone"],
            ["code" => "HP00348", "name" => "HP XIAOMI REDMI 12C 4/128GB", "price" => "1799000", "cost" => "1681000", "category" => "handphone"],
            ["code" => "HP00350", "name" => "HP REALME C21Y 3/32GB", "price" => "1599000", "cost" => "1495000", "category" => "handphone"],
            ["code" => "HP00351", "name" => "HP INFINIX HOT 20I 4/128GB", "price" => "1899000", "cost" => "1532000", "category" => "handphone"],
            ["code" => "HP00352", "name" => "HP XIAOMI NOTE 10S 8/128GB", "price" => "3099000", "cost" => "2895000", "category" => "handphone"],
            ["code" => "HP00353", "name" => "HP XIAOMI REDMI 12C 4/64GB", "price" => "1599000", "cost" => "1494000", "category" => "handphone"],
            ["code" => "HP00354", "name" => "HP REALME C25S 4/128GB", "price" => "2499000", "cost" => "2199000", "category" => "handphone"],
            ["code" => "HP00355", "name" => "HP SAMSUNG A32 8/128GB", "price" => "3699000", "cost" => "3347070", "category" => "handphone"],
            ["code" => "HP00359", "name" => "HP OPPO RENO 6 4G 8/128GB", "price" => "5199000", "cost" => "4500000", "category" => "handphone"],
            ["code" => "HP00361", "name" => "HP OPPO A78 5G 8/128GB", "price" => "3999000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00362", "name" => "HP VIVO V27 8/256GB (DASEP)", "price" => "3899000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00364", "name" => "HP SAMSUNG A22 6/128GB 5G", "price" => "3399000", "cost" => "2882070", "category" => "handphone"],
            ["code" => "HP00365", "name" => "HP INFINIX NOTE 12I 6/128GB", "price" => "2499000", "cost" => "2000000", "category" => "handphone"],
            ["code" => "HP00366", "name" => "HP OPPO A16 3/32GB", "price" => "1899000", "cost" => "1660000", "category" => "handphone"],
            ["code" => "HP00367", "name" => "HP SAMSUNG A04E 3/64GB", "price" => "1399000", "cost" => "1325000", "category" => "handphone"],
            ["code" => "HP00369", "name" => "HP VIVO Y17", "price" => "2350000", "cost" => "2160000", "category" => "handphone"],
            ["code" => "HP00370", "name" => "HP SAMSUNG A52 8/128GB", "price" => "4999000", "cost" => "4649070", "category" => "handphone"],
            ["code" => "HP00371", "name" => "HP XIAOMI POCO X3 PRO 6/128", "price" => "3600000", "cost" => "3365000", "category" => "handphone"],
            ["code" => "HP00372", "name" => "HP VIVO Y22 4/128GB", "price" => "2399000", "cost" => "2160000", "category" => "handphone"],
            ["code" => "HP00374", "name" => "HP REALME 10 8/256GB", "price" => "3599000", "cost" => "3311000", "category" => "handphone"],
            ["code" => "HP00375", "name" => "HP OPPO A5S 3GB", "price" => "2099000", "cost" => "1750053", "category" => "handphone"],
            ["code" => "HP00376", "name" => "HP VIVO V21 5G 8/128GB", "price" => "4499000", "cost" => "4050000", "category" => "handphone"],
            ["code" => "HP00377", "name" => "HP SAMSUNG A22 6/128GB", "price" => "2999000", "cost" => "2789070", "category" => "handphone"],
            ["code" => "HP00378", "name" => "HP VIVO V27E 8/256GB", "price" => "4299000", "cost" => "3870000", "category" => "handphone"],
            ["code" => "HP00380", "name" => "HP VIVO V27 8/256GB", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00382", "name" => "HP EVERCOSS N1D", "price" => "210000", "cost" => "140000", "category" => "handphone"],
            ["code" => "HP00384", "name" => "HP INFINIX SMART 7 3/64GB", "price" => "1299000", "cost" => "1120000", "category" => "handphone"],
            ["code" => "HP00385", "name" => "HP INFINIX HOT 20S 8/128GB", "price" => "2699000", "cost" => "2319000", "category" => "handphone"],
            ["code" => "HP00389", "name" => "HP VIVO Y16 4/128GB", "price" => "2199000", "cost" => "1820000", "category" => "handphone"],
            ["code" => "HP00390", "name" => "HP VIVO V27E 12/256GB", "price" => "4999000", "cost" => "4410000", "category" => "handphone"],
            ["code" => "HP00394", "name" => "HP SAMSUNG A14 5G 6/128GB", "price" => "2999000", "cost" => "2765000", "category" => "handphone"],
            ["code" => "HP00396", "name" => "HP NOKIA 210", "price" => "510000", "cost" => "440000", "category" => "handphone"],
            ["code" => "HP00397", "name" => "HP OPPO RENO 8T 5G 8/256GB", "price" => "5999000", "cost" => "5850000", "category" => "handphone"],
            ["code" => "HP00399", "name" => "HP VIVO Y12 3GB/32", "price" => "1899000", "cost" => "1800000", "category" => "handphone"],
            ["code" => "HP00400", "name" => "HP OPPO FIND N2 FLIP 8/256GB", "price" => "14999000", "cost" => "13500000", "category" => "handphone"],
            ["code" => "HP00401", "name" => "HP EVERCOSS N1F", "price" => "200000", "cost" => "152000", "category" => "handphone"],
            ["code" => "HP00406", "name" => "HP OPPO A5 RAM 4GB/128", "price" => "2990000", "cost" => "2560000", "category" => "handphone"],
            ["code" => "HP00410", "name" => "HP XIAOMI REDMI 10 2022 6/128GB", "price" => "2399000", "cost" => "2149000", "category" => "handphone"],
            ["code" => "HP00411", "name" => "HP REALME C33 4/128GB", "price" => "1999000", "cost" => "1839000", "category" => "handphone"],
            ["code" => "HP00413", "name" => "HP VIVO Y19 6/128", "price" => "2999000", "cost" => "2836250", "category" => "handphone"],
            ["code" => "HP00414", "name" => "HP XIAOMI REDMI A2 3/32GB", "price" => "1299000", "cost" => "1074000", "category" => "handphone"],
            ["code" => "HP00415", "name" => "HP INFINIX SMART 7 4/64GB", "price" => "1399000", "cost" => "1220000", "category" => "handphone"],
            ["code" => "HP00418", "name" => "HP XIAOMI REDMI 9A 2/32", "price" => "1299000", "cost" => "1214000", "category" => "handphone"],
            ["code" => "HP00420", "name" => "HP INFINIX HOT 30I 8/128GB", "price" => "1799000", "cost" => "1520000", "category" => "handphone"],
            ["code" => "HP00425", "name" => "HP REALME 5I 4/64", "price" => "1999000", "cost" => "2030000", "category" => "handphone"],
            ["code" => "HP00428", "name" => "HP VIVO Y36 8/256GB", "price" => "3399000", "cost" => "3093000", "category" => "handphone"],
            ["code" => "HP00433", "name" => "HP NOKIA 110", "price" => "400000", "cost" => "330000", "category" => "handphone"],
            ["code" => "HP00443", "name" => "HP XIAOMI REDMI NOTE 12 6/128GB", "price" => "2999000", "cost" => "2616000", "category" => "handphone"],
            ["code" => "HP00462", "name" => "HP EVERCOSS U50A MAX 2/16", "price" => "850000", "cost" => "760000", "category" => "handphone"],
            ["code" => "HP00463", "name" => "HP REALME C11 2/32", "price" => "1599000", "cost" => "1393028", "category" => "handphone"],
            ["code" => "HP00469", "name" => "HP VIVO Y12i 3/32", "price" => "1899000", "cost" => "1710000", "category" => "handphone"],
            ["code" => "HP00471", "name" => "HP INFINIX HOT 9 PLAY 2/32", "price" => "1450000", "cost" => "1245000", "category" => "handphone"],
            ["code" => "HP00475", "name" => "HP XIAOMI REDMI NOTE 12 PRO 6/128GB", "price" => "3499000", "cost" => "3270000", "category" => "handphone"],
            ["code" => "HP00477", "name" => "HP VIVO Y02T 4/64GB", "price" => "1699000", "cost" => "1580000", "category" => "handphone"],
            ["code" => "HP00478", "name" => "HP REALME C53 6/128GB", "price" => "2099000", "cost" => "1931000", "category" => "handphone"],
            ["code" => "HP00479", "name" => "HP INFINIX HOT 30 8/128GB", "price" => "2099000", "cost" => "1800000", "category" => "handphone"],
            ["code" => "HP00492", "name" => "HP XIAOMI REDMI 9A 3/32GB", "price" => "1449000", "cost" => "1354000", "category" => "handphone"],
            ["code" => "HP00503", "name" => "HP XIAOMI REDMI 9C 4/64", "price" => "1950000", "cost" => "1680000", "category" => "handphone"],
            ["code" => "HP00511", "name" => "HP VIVO V25 8/256GB", "price" => "5999000", "cost" => "5400000", "category" => "handphone"],
            ["code" => "HP00518", "name" => "HP ADVAN NASA PLUS 2/16GB", "price" => "900000", "cost" => "770000", "category" => "handphone"],
            ["code" => "HP00528", "name" => "HP ADVAN G5 ELITE", "price" => "1150000", "cost" => "988731", "category" => "handphone"],
            ["code" => "HP00530", "name" => "HP SAMSUNG A12 4/128", "price" => "2299000", "cost" => "2138070", "category" => "handphone"],
            ["code" => "HP00531", "name" => "HP SAMSUNG A12 6/128", "price" => "2850000", "cost" => "2324070", "category" => "handphone"],
            ["code" => "HP00539", "name" => "HP REALME 8 8/128GB", "price" => "3599000", "cost" => "3239000", "category" => "handphone"],
            ["code" => "HP00542", "name" => "HP REALME 8 PRO 8/128GB", "price" => "4499000", "cost" => "3869000", "category" => "handphone"],
            ["code" => "HP00547", "name" => "HP SAMSUNG A32 6/128GB", "price" => "3599000", "cost" => "3347070", "category" => "handphone"],
            ["code" => "HP00548", "name" => "HP VIVO V21 4G 8/128GB", "price" => "3999000", "cost" => "3600000", "category" => "handphone"],
            ["code" => "HP00549", "name" => "HP VIVO Y53s", "price" => "3699000", "cost" => "3330000", "category" => "handphone"],
            ["code" => "HP00550", "name" => "HP XIAOMI POCO X3 PRO 8/256", "price" => "3899000", "cost" => "3830000", "category" => "handphone"],
            ["code" => "HP00551", "name" => "HP SAMSUNG GALAXY A52 8/256", "price" => "5275000", "cost" => "5021070", "category" => "handphone"],
            ["code" => "HP00552", "name" => "HP REALME C21y 4/64", "price" => "1799000", "cost" => "1682000", "category" => "handphone"],
            ["code" => "HP00555", "name" => "HP EVERCOSS SP5 PRO", "price" => "395000", "cost" => "285000", "category" => "handphone"],
            ["code" => "HP00556", "name" => "HP OPPO RENO6 8/128", "price" => "5199000", "cost" => "4500000", "category" => "handphone"],
            ["code" => "HP00557", "name" => "HP REALME C11 2021 2/32", "price" => "1349000", "cost" => "1261000", "category" => "handphone"],
            ["code" => "HP00558", "name" => "HP XIAOMI REDMI NOTE 10s 8/128GB", "price" => "2699000", "cost" => "2512000", "category" => "handphone"],
            ["code" => "HP00560", "name" => "HP EVERCOSS M60 2/16", "price" => "900000", "cost" => "725000", "category" => "handphone"],
            ["code" => "HP00564", "name" => "HP XIAOMI REDMI 9c 3/32GB", "price" => "1750000", "cost" => "1495000", "category" => "handphone"],
            ["code" => "HP00565", "name" => "HP VIVO Y21s 4/128GB", "price" => "2799000", "cost" => "2430000", "category" => "handphone"],
            ["code" => "HP00566", "name" => "HP VIVO Y21s LDU (DEMO) 4/128GB", "price" => "1680000", "cost" => "1680000", "category" => "handphone"],
            ["code" => "HP00567", "name" => "TABLET EVERCOSS U70c Plus", "price" => "1000000", "cost" => "825000", "category" => "handphone"],
            ["code" => "HP00569", "name" => "HP VIVO Y21 / Y21A 4/64GB", "price" => "2099000", "cost" => "1890000", "category" => "handphone"],
            ["code" => "HP00570", "name" => "HP SAMSUNG GALAXY A03s 4/64", "price" => "1799000", "cost" => "1673070", "category" => "handphone"],
            ["code" => "HP00576", "name" => "HP OPPO A16 4/64GB", "price" => "2499000", "cost" => "2250000", "category" => "handphone"],
            ["code" => "HP00577", "name" => "HP XIAOMI REDMI 10 4/64", "price" => "2299000", "cost" => "1952000", "category" => "handphone"],
            ["code" => "HP00578", "name" => "HP XIAOMI REDMI 10 6/128GB", "price" => "2499000", "cost" => "2336000", "category" => "handphone"],
            ["code" => "HP00580", "name" => "HP EVERCOSS M6A 3/16", "price" => "950000", "cost" => "888000", "category" => "handphone"],
            ["code" => "HP00581", "name" => "HP SAMSUNG A52S 5G 8/256", "price" => "5999000", "cost" => "5579070", "category" => "handphone"],
            ["code" => "HP00583", "name" => "HP VIVO Y33S (DEMO) 8/128", "price" => "3399000", "cost" => "2040000", "category" => "handphone"],
            ["code" => "HP00584", "name" => "HP VIVO Y33S 8/128GB", "price" => "3399000", "cost" => "2970000", "category" => "handphone"],
            ["code" => "HP00585", "name" => "HP REALME C11 2021 4/64", "price" => "1699000", "cost" => "1589000", "category" => "handphone"],
            ["code" => "HP00587", "name" => "HP XIAOMI REDMI NOTE 10 PRO 6/128", "price" => "3799000", "cost" => "3550000", "category" => "handphone"],
            ["code" => "HP00588", "name" => "HP VIVO Y15S 3/32GB", "price" => "1699000", "cost" => "1580000", "category" => "handphone"],
            ["code" => "HP00589", "name" => "HP VIVO Y15S DEMO", "price" => "1999000", "cost" => "1140000", "category" => "handphone"],
            ["code" => "HP00591", "name" => "HP VIVO V23E 8/128GB LDU", "price" => "2920000", "cost" => "2820000", "category" => "handphone"],
            ["code" => "HP00592", "name" => "HP LUNA G9 4/32 GB", "price" => "1350000", "cost" => "1225000", "category" => "handphone"],
            ["code" => "HP00593", "name" => "HP INFINIX HOT 11 4/64GB", "price" => "1850000", "cost" => "1722000", "category" => "handphone"],
            ["code" => "HP00594", "name" => "HP INFINIX SMART 6 2/32 GB", "price" => "1350000", "cost" => "1177000", "category" => "handphone"],
            ["code" => "HP00595", "name" => "HP REALME C25Y 4/64GB", "price" => "2099000", "cost" => "1776000", "category" => "handphone"],
            ["code" => "HP00597", "name" => "HP OPPO A95 8/128 GB", "price" => "3999000", "cost" => "3420000", "category" => "handphone"],
            ["code" => "HP00598", "name" => "HP REALME 8I 6/128GB", "price" => "2799000", "cost" => "2575000", "category" => "handphone"],
            ["code" => "HP00599", "name" => "HP VIVO V23E 8/128GB", "price" => "3799000", "cost" => "3420000", "category" => "handphone"],
            ["code" => "HP00600", "name" => "HP OPPO A95 8/128 (DEMO)", "price" => "2000000", "cost" => "2000000", "category" => "handphone"],
            ["code" => "HP00601", "name" => "HP EVERCOSS N1E", "price" => "260000", "cost" => "179000", "category" => "handphone"],
            ["code" => "HP00602", "name" => "HP SAMSUNG GALAXY A03 CORE 2/32", "price" => "1249000", "cost" => "1161570", "category" => "handphone"],
            ["code" => "HP00603", "name" => "HP REALME 8I 4/64GB", "price" => "2499000", "cost" => "2436000", "category" => "handphone"],
            ["code" => "HP00604", "name" => "HP INFINIX ZERO X NEO 8/128GB", "price" => "3299000", "cost" => "2967000", "category" => "handphone"],
            ["code" => "HP00605", "name" => "HP ITEL 2320", "price" => "250000", "cost" => "192000", "category" => "handphone"],
            ["code" => "HP00606", "name" => "HP OPPO A77s 8/128GB (DEMO)", "price" => "2000000", "cost" => "1750000", "category" => "handphone"],
            ["code" => "HP00607", "name" => "HP OPPO A77s 8/128GB", "price" => "3499000", "cost" => "2970000", "category" => "handphone"],
            ["code" => "HP00608", "name" => "HP OPPO A17 4/64GB", "price" => "1999000", "cost" => "1800000", "category" => "handphone"],
            ["code" => "HP00609", "name" => "HP REALME C33 3/32GB", "price" => "1599000", "cost" => "1496000", "category" => "handphone"],
            ["code" => "HP00610", "name" => "HP REALME C33 4/64GB", "price" => "1799000", "cost" => "1682000", "category" => "handphone"],
            ["code" => "HP00611", "name" => "HP REALME 10 8/128 GB", "price" => "3199000", "cost" => "2943000", "category" => "handphone"],
            ["code" => "HP00612", "name" => "HP OPPO RENO 8 PRO 5G 12/256", "price" => "9999000", "cost" => "9000000", "category" => "handphone"]
        ];

        Product::insert($products);
    }
}
