<?php

require_once dirname(dirname(__FILE__)) . '/config/init.php';
$pdo = Zend_Registry::get('doctrine')->getDbh();
$raceIds = array(531601,531604,531605,531611,531612,531616,531618,531626,531629,531635,531637,531641,531645,531655,531657,531662,536258,536262,536294,536300,536344,536346,536353,536357,536361,536364,536392,536393,536396,536399,536436,536438,536457,536458,536478,536479,536480,536481,536482,536488,536491,536492,536496,536497,536500,536502,536506,536508,536510,536516,536517,536520,536521,536524,536526,536527,536531,536541,536546,536547,536549,536550,536552,536554,536556,536557,536559,536560,536561,536565,536574,536575,536588,536591,536592,536593,536594,536595,536596,536597,536599,536600,536601,536603,536604,536605,536606,536607,536608,536609,536610,536614,536657,536660,536663,536665,536668,536671,536672,536674,536677,536678,536679,536680,536682,536686,536687,536689,536690,536692,536693,536694,536695,536697,536699,536701,536703,536704,536706,536707,536709,536711,536712,536714,536716,536717,536719,536720,536721,536772,536774,536778,536781,536783,536785,536787,536868,536873,536874,536877,536878,536879,536881,536882,536884,536885,536890,536892,536893,536894,536895,536897,536900,536902,536903,536905,536907,536908,536911,536912,536915,536916,536917,536919,536922,536923,536926,536930,536932,537521,537536,537963,537966,537967,538514,552055,552059,552068,552075,552076,552084,552087,552098,552102,552103,552105,552106,552117,552119,552121,552123,552124,552132,552136,553380,553384,553386,553390,553391,553393,553401,553404,553409,553413,553416,553417,553419,553421,553422,553423,553424,553425,553426,553427,553428,553429,553431,553433,553434,553436,553437,553438,553441,553442,553443,553449,553453,554251,554253,554256,554258,554260,554261,554263,554266,554268,554272,554275,554276,554280,554284,554286,554298,554302,554305,554311,554313,554321,554323,554334,554336,554351,554359,554361,554364,554367,554369,554372,554375,554380,554381,554382,554392,554395,554404,554411,554412,554415,554417,554418,554419,554423,554428,554432,554433,554434,554435,554436,554439,554441,554444,554448,554449,554451,554452,554456,554458,554459,554460,554462,554463,554465,554482,554483,556113,556118,556126,556133,556137,556141,556151,556158,556167,556169,556187,556202,556204,556206,556209,556213,556223,556306,556307,556309,556311,556312,556313,556315,556316,556317,556319,556320,556323,556324,556328,557460,557465,557469,557706,557707,557710,557714,557718,557722,557725,557727,557730,557732,557736,557737,557740,557741,557744,557745,557746,557748,557749,557754,557756,557760,557764,557768,557777,557781,557783,557785,557789,557791,557793,557795,557796,557803,557805,557806,557807,557808,557810,557812,557813,557816,557819,557828,557829,557838,557840,557843,557846,557855,557887,557892,557897,557901,557920,557923,557936,557941,557943,557945,557947,557951,557954,557979,557986,557987,557992,557998,558130,558153,558155,558177,558301,558302,558304,558307,558358,558360,558372,558379,558382,558386,558393,559045,559080,559081,559082,559088,559089,559091,559092,559093,559095,559097,559098,559100,559613,559618,559627,559629,559630,559631,559632,559633,559637,559639,559641,559643,559644,559648,559649,559652,559653,559656,559659,559662,559667,559668,559669,559674,559682,559699,559702,559719,559722,560289,560295,560324,560552,560553,560803,560823,560840,560853,560857,560866,560884,560886,560888,560890,560897,560903,560909,560920,560923,560973,560978,560991,561001,561003,561006,561008,561015,561016,561017,561018,561021,561022,561025,561026,561028,561029,561031,561033,561034,561037,561039,561041,561042,561045,561049,561050,561051,561055,561058,561059,561068,561069,561074,561081,561082,561083,561084,561087,561093,561096,561099,561118,561122,561130,561133,561146,561188,561607,561611,561924,561927,561928,561929,561930,561931,561932,561933,561934,561935,561936,561937,561939,561940,561941,561942,561943,561944,561945,561949,561954,561958,561961,561962,561965,561966,561967,561968,561969,561970,561971,561972,561973,561976,561979,561990,562193,562196,562199,562209,562211,562214,562221,562257,562264,562265,562266,562267,562268,562269,562270,562271,562272,562273,562275,562276,562278,562279,562280,562281,562282,562284,562285,563704,563708,563713,563724,563729,563734,563736,563737,563741,563746,563747,563749,563751,563752,563753,563754,563756,563758,563761,563762,563763,563764,563766,563767,563768,563769,563772,563775,563783,563784,563788,564059,564068,564076,564110,564243,564245,564247,564249,564250,564251,564254,564275,565647,565651,565653,565717,565720,565722,565725,565727,565731,565741,565742,565744,565749,565764,565775,565781,565787,567788,567793,567799,567902,567944,567945,567959,568322,568324,568336,568359,568365,568370,568380,575047,575048,575049,575050,575051,575052,575053,575054,575055,575056,575057,575058,575059,575060,575061,575062,575063,575064,575065,575066,575067,575068,575069,575070,575071,575072,575073,575074,575075,575076,575077,575078,575079,575080,575081,575082,575083,575084,575085,575086,575087,575088,575100,575218,575219,575236,575237,575239,575240,575241,575242,575243,575244,575245,575247,575249,575250,577189,577191,577200,577207,577217,577223,577224,577226,577227,577228,577230,577231,577233,577236,577243,577246,577247,577252,577254,577255,577256,577267,577269,577274,577277,577283,577285,577288,577293,577306,577307,577317,577320,577321,577324,577328,577331,577335,577339,577341,577343,577344,577347,577349,577351,577354,577355,577357,577358,577361,577363,577364,577365,577367,577368,577370,577371,577375,577376,577384,577386,577388,577390,577402,577404,577406,577407,577411,577412,577416,577417,577419,577423,577425,577426,577429,577432,577434,577435,577438,577439,577441,577443,577444,577447,577448,577450,577452,577454,577455,577457,577458,577460,577462,577465,577466,577468,577471,577472,577475,577476,577477,577478,577479,577480,577481,577482,580155,580166,580167,580168,580170,580171,580173,580175,580176,580179,580180,580181,580182,580184,580186,580187,580188,580189,580190,580191,580192,580193,580194,580195,580196,580197,580198,580199,580200,580201,580202,580203,580204,580205,580206,580208,580209,580210,580211,580212,581186,581189,581242,581244,581251,581258,581263,581267,581269,581277,581279,581290,581294,581296,581298,581303,581304,581310,581313,581315,581316,581318,581319,581320,581321,581322,581323,581325,581329,581330,581332,581333,581334,581336,581337,581338,581340,581341,581342,581343,581344,581345,581346,581347,581348,581349,581350,581351,581353,581354,581356,581357,581359,581360,581362,581363,581364,581365,581366,581367,581368,581369,581370,581371,581372,581373,581374,581375,581376,581377,581378,581379,581380,581381,581382,581383,581385,581386,581387,581388,581389,581390,581391,581392,581394,581395,581396,581398,581400,581403,581404,581405,581406,581408,581409,581410,581411,581412,581413,581417,581418,581419,581421,581423,581425,581426,581427,581428,581429,581430,581431,581437,581446,581448,581451,581454,581457,581459,581460,581464,581466,581467,581470,581481,581487,582156,582179,582217,582223,582226,582232,582236,582242,582253,582254,582257,582259,582261,582263,582321,582325,582447,583800,583806,583810,583812,583814,583833,583845,583864,583875,583881,584216,584218,584221,584222,584223,584229,584232,584236,584239,584243,584250,584252,584255,584259,584263,584279,584281,584294,584296,584300,584301,584303,584304,584306,584307,584312,584315,584317,584320,584323,584330,584341,584368,584377,584381,584420,584426,584438,584448,584454,584460,584462,584474,584482,584485,584487,584491,584497,584507,584518,584522,584534,584535,584542,584552,584562,584567,584618,584624,584633,584635,584641,584662,584663,584667,584673,584676,584680,584683,584686,584688,584689,584691,584692,584693,584696,584700,584705,584707,584709,584710,584711,584712,584713,584718,584722,584723,584726,585050,585051,585052,585054,585057,585059,585060,585061,585062,585065,585070,585072,585145,585146,585229,585254,585255,585256,585299,585300,585301,585302,585303,585304,585305,585306,585307,585308,585309,585310,585311,585312,585313,585314,585374,585384,585385,585387,585389,585391,585392,585394,585395,585397,585402,585403,585407,585412,585417,585428,585437,585439,585441,585443,585445,585446,585447,585448,585449,585451,585460,585461,585462,585464,585465,585468,585472,585475,585476,585479,585528,585536,585537,585539,585543,585554,585555,585557,585559,585563,585566,585570,585571,585573,585575,585580,585583,585585,585586,585588,585589,585591,585592,585593,585594,585595,585596,585598,585600,585601,585602,585604,585605,585607,585609,585610,585611,585612,585615,585619,585620,585621,585622,585624,585626,585628,585629,585630,585631,585632,585633,585635,585636,585637,585639,585640,585641,585642,585643,585644,585645,585646,585647,585648,585649,585650,585651,585652,585654,585655,585656,585657,585658,585659,585660,585661,585662,585664,585665,585666,585667,585668,585669,585670,585671,585672,585673,585674,585675,585676,585677,585678,585681,585682,585683,585685,585686,585687,585689,585690,585691,585694,585695,585697,585699,585700,585701,585703,585704,585705,585706,585707,585708,585709,585710,585711,585712,585713,585714,585715,585716,585717,585718,585719,585720,585721,599666,599668,599670,600064,600065,600067,600088,600089,600090,600712,600718,601507,601948,601954,601955,601962,602015,602017,602035,602037,602044,602108,602111,602122,602125,602278,602279,602280,602281,602282,602283,602284,602285,602286,602288,602289,602290,602340,602341,602412,602413,602414,602415,602797,602912,602917,602919,602920,602924,603003,603005,603006,603008,603009,603011,603012,603015,603016,603018,603025,603027,603028,603030,603031,603033,603035,603048,603062,603066,603068,603069,603073,603075,603077,603079,603081,603082,603083,603084,603085,603087,603088,603089,603090,603092,603093,603094,603095,603096,603097,603099,603100,603103,603105,603106,603107,603109,603111,603113,603114,603115,603116,603118,603120,603121,603122,603124,603125,603126,603127,603128,603129,603132,603133,603134,603135,603136,603138,603139,603140,603142,603143,603144,603145,603146,603147,603148,603149,603150,603151,603152,603153,603154,603156,603157,603158,603159,603160,603161,603162,603164,603165,603166,603167,603168,603170,603171,603173,603176,603177,603178,603179,603180,603181,603183,603184,603185,603186,603187,603188,603189,603190,603191,603192,603193,603194,603195,603196,603197,603266,603274,603276,603278,603297,603298,603300,603301,603305,603306,603310,603313,603316,603320,603322,603327,603328,603329,603332,603336,603337,603341,603344,603348,603353,603354,603362,603363,603364,603365,603366,603368,603369,603371,603373,603374,603375,603376,603377,603379,603380,603381,603389,603390,603391,603400,603402,603404,603405,603415,603422,603425,603439,603475,603481,603503,603516,603539,603614,603617,603621,603658,603662,603663,603666,603667,603671,603673,603677,603679,603684,603690,603694,603702,603703,603705,603706,603707,603708,603710,603711,603712,603713,603714,603715,603717,603718,603719,603720,603721,603722,603723,603724,603725,603727,603728,603729,603730,603731,603733,603734,603737,603739,603740,603741,603742,603743,603744,603747,603750,603751,603752,603753,603755,603756,603757,603758,603759,603761,603762,603763,603764,603765,603766,603767,603768,603769,603772,603773,603775,603776,603779,603781,603783,603786,603787,603788,603789,603791,603793,603795,603797,603798,603799,603800,603802,603805,603807,603808,603809,603810,603812,603814,603815,603816,603817,603818,603819,603820,603821,603822,603823,603824,603825,603826,603827,603828,603829,603830,603831,603832,603833,603834,603835,603836,603837,603838,603839,603840,603841,603842,603843,603844,603845,603846,603847,603848,603849,603850,603851,603852,603853,603854,603855,603856,603857,603858,603859,603860,603861,603862,603863,603864,603865,603866,603867,603868,603869,603870,603871,603872,603873,603874,603875,603876,603877,603878,603879,603880,603881,603882,603883,603884,603885,603886,603887,603888,603889,603890,603891,603892,603893,603894,603895,603896,603897,603898,603899,603900,603901,603902,603903,603904,603905,603906,603907,603908,603909,603910,603911,603912,603913,603914,603915,603916,603917,603918,603919,603920,603921,603922,603923,603924,603925,603926,603927,603928,603929,603930,603931,603932,603933,603934,603935,603936,603937,603938,603939,603940,603941,603942,603943,603944,603945,603946,603947,603948,603949,603950,603951,603952,603953,603954,603955,603956,603957,603958,603959,603960,603961,603962,603963,603964,603965,603966,603967,603968,603969,603970,603971,603972,603973,603974,603975,603977,603978,603980,603981,603983,603984,603986,603987,603988,603989,603990,603991,603992,603993,603994,603995,603996,603997,603998,604000,604001,604003,604005,604010,604011,604013,604014,604015,604016,604018,604020,604022,604024,604026,604027,604028,604030,604031,604033,604036,604037,604038,604040,604041,604044,604045,604047,604048,604050,604051,604052,604055,604057,604058,604060,604061,604063,604065,604066,604067,604068,604069,604070,604073,604075,604076,604077,604078,604079,604081,604082,604086,604087,604089,604092,604093,604094,604099,604104,604105,604108,604110,604111,604114,604115,604119,604124,604125,604128,604131,604133,604137,604138,604139,604140,604141,604151,604319,604333,604419,604444,604446,604458,604474,604477,604480,604482,604485,604486,604493,604495,604498,604500,604509,604523,604526,604533,604534,604535,604538,604539,604545,604550,604551,604552,604553,604554,604555,604556,604557,604558,604559,604560,604561,604562,604563,604564,604565,604566,604567,604568,604569,604570,604571,604572,604573,604574,604575,604576,604577,604578,604579,604580,604581,604582,604583,604584,604585,604586,604587,604588,604589,604590,604591,604592,604593,604594,604595,604596,604597,604598,604599,604600,604601,604602,604603,604604,604605,604606,604607,604608,604609,604610,604611,604612,604613,604614,604615,604616,604617,604618,604619,604620,604621,604622,604623,604624,604625,604626,604627,604628,604629,604630,604631,604632,604633,604634,604635,604636,604637,604638,604639,604640,604641,604642,604643,604644,604645,604646,604647,604648,604649,604650,604651,604652,604653,604654,604655,604656,604657,604658,604659,604660,604661,604662,604663,604664,604665,604666,604667,604668,604669,604670,604671,604672,604673,604674,604675,604676,604677,604678,604679,604680,604681,604682,604683,604684,604685,604686,604687,604688,604689,604690,604691,604692,604693,604694,604695,604696,604697,604698,604699,604700,604701,604702,604703,604704,604705,604706,604707,604708,604709,604710,604711,604712,604713,604714,604715,604716,604717,604718,604719,604720,604721,604722,604723,604724,604725,604726,604727,604728,604729,604730,604731,604732,604733,604734,604735,604736,604737,604738,604739,604740,604741,604742,604743,604744,604745,604746,604747,604748,604749,604750,604751,604752,604753,604754,604755,604756,604757,604758,604759,604760,604761,604762,604763,604764,604765,604766,604767,604768,604769,604770,604771,604772,604773,604774,604775,604779,604780,604782,604783,604786,604788,604789,604790,604791,604792,604793,604795,604796,604797,604798,604799,604800,604802,604803,604804,604805,604806,604808,604809,604810,604811,604812,604815,604816,604817,605755,605772,605774,605777,605778,605780,605786,605793,605800,605812,605820,605827,605852,605864,605873,605880,605883,605901,605902,605903,605905,605910,605915,605918,605919,605925,605930,605931,605937,605948,605951,605954,605956,605991,605993,606023,606026,606029,606030,606036,606037,606039,606040,606042,606043,606044,606048,606050,606052,606054,606055,606056,606058,606060,606061,606062,606063,606064,606065,606066,606068,606069,606070,606072,606073,606074,606075,606076,606077,606078,606079,606080,606081,606082,606083,606084,606085,606086,606087,606088,606091,606092,606094,606095,606096,606097,606098,606099,606100,606101,606102,606103,606104,606106,606107,606108,606109,606111,606112,606113,606114,606115,606116,606119,606121,606124,606126,606127,606128,606129,606130,606131,606132,606133,606134,606135,606136,606137,606138,606139,606140,606141,606142,606143,606144,606145,606146,606147,606148,606150,606151,606153,606154,606155,606157,606158,606159,606160,606161,606162,606163,606164,606165,606166,606167,606168,606169,606170,606171,606172,606173,606174,606175,606176,606178,606179,606182,606183,606185,606186,606187,606191,607036,607037,607049,607051,607055,607056,607058,607060,607062,607064,607066,607068,607069,607079,607085,607121,607132,607137,607141,607148,607150,607151,607152,607155,607156,607171,607175,607179,607223,607227,607231,607331,607370,607382,607383,607388,607392,607394,607401,607405,607406,607407,607411,607418,607422,607424,607429,607444,607744,607747,607749,607756,607758,607759,607763,607764,607767,607769,607783,607785,607788,607791,607795,607798,607803,607805,607807,607809,607811,608060,608079,608081,608083,608157,608163,608168,608171,608222,608441,608459,608461,608467,608470,608472,608477,608483,608484,608489,608529,608537,608538,608542,608543,608547,608551,608561,608569,608590,608620,608624,608635,608647,608659,608663,608665,608666,608668,608669,608675,608676,608685,608690,608705,608711,608765,608909,608917,608938,608988,609362,609365,609366,609368,609370,609371,609372,609373,609374,609375,609376,609377,609378,609381,609385,609391,609547,609548,609549,609550,609551,609552,609556,609558,609559,609625,609627,609661,609670,609682,609688,609693,609727,609736,609737,609738,609739,609742,609744,609745,609746,609748,609760,609764,609765,609767,609768,609775,609777,609781,609782,609785,609788,609793,609799,609803,609805,609806,609819,609821,609822,609823,609824,609825,609826,609828,609829,609830,609831,609832,609833,609834,609835,609837,609838,609839,609840,609841,609842,609843,609844,609845,609848,609851,609852,609855,609857,609859,609860,609864,609865,609867,609868,609870,609962,609965,609966,609974,609975,609978,609981,609982,609987,609989,609992,609993,609996,610135,610136,610139,610140,610142,610143,610145,610146,610147,610148,610152,610153,610154,610155,610157,610159,610162,610164,610165,610166,610185,610220,610221,610223,610226,610231,610232,610233,610234,610235,610236,610237,610238,610239,610240,610241,610242,610244,610245,610246,610247,610248,610249,610250,610251,610252,610253,610254,610257,610258,610259,610260,610261,610262,610264,610265,610266,610267,610268,610269,610270,610271,610272,610275,610276,610277,610278,610281,610282,610283,610285,610286,610287,610288,610290,610291,610292,610294,610295,610296,610297,610298,610300,610301,610302,610304,610307,610310,610311,611060,611061,611067,611074,611078,611080,611082,611083,611084,611085,611093,611094,611096,611097,611098,611099,611100,611101,611103,611105,611107,611108,611109,611112,611114,611117,611118,611119,611120,611122,611125,611126,611127,611128,611129,611130,611131,611134,611135,611138,611139,611140,611141,611143,611145,611146,611147,611149,611150,611151,611153,611154,611155,611157,611158,611160,611161,611169,611170,611171,611175,611176,611178,611180,611181,611187,611188,611193,611195,611199,611201,611202,611203,611214,611218,611222,611226,611227,611231,611238,611244,611247,611251,611254,611256,611257,611258,611261,611277,611279,611284,611288,611290,611293,611296,611300,611306,611308,611310,611312,611313,611315,611316,611317,611319,611320,611323,611326,611328,611329,611331,611337,611339,611340,611342,611345,611346,611352,611362,611367,611381,611390,611395,611397,611399,611403,611408,611418,611424,611428,611436,611441,611445,611446,611449,611453,611455,611456,611469,611471,611472,611474,611476,611477,611480,611481,611483,611496,611538,611543,611544,611547,611548,611549,611550,611551,611558,611561,611562,611563,611564,611565,611566,611567,611568,611595,611995,611996,611998,612020,612023,612026,612027,612028,612032,612036,612041,612046,612053,612054,612055,612057,612060,612074,612089,612091,612092,612095,612096,612097,612099,612103,612106,612107,612109,612111,612112,612114,612116,612117,612119,612121,612122,612123,612124,612127,612128,612130,612133,612142,612154,612158,612162,612166,612167,612171,612172,612178,612180,612182,612184,612185,612186,612187,612188,612189,612190,612191,612192,612193,612194,612195,612196,612197,612198,612202,612204,612207,612208,612209,612210,612213,612214,612220,612344,612367,612374,612426,612555,612651,612838,612842,612858,612868,612879,612882,612918,612944,612950,612973,612978,612993,613056,613094,613095,613120,613121,613133,613156,613158,613164,613167,613171,613173,613174,613175,613177,613187,613191,613193,613475,613528,613535,613551,613627,613673,613874,613877,613878,613880,613883,613885,613893,613897,613900,613903,613906,613907,613909,613911,613914,613945,613955,613962,613963,613965,613970,613978,613984,613989,613993,614015,614025,614028,614030,614033,614035,614039,614124,614136,614182,614194,614205,614214,614234,614281,614282,614286,614290,614292,614295,614296,614301,614307,614313,614316,614325,614326,614328,614342,614346,614347,614353,614355,614356,614357,614358,614363,614369,614395,614396,614398,614403,614405,614407,614409,614411,614412,614414,614416,614418,614423,614427,614428,614430,614433,614434,614446,614448,614457,614459,614460,614466,614482,614488,614495,614511,614558,614563,614565,614585,614631,614660,614663,614665,614672,614675,614794,614815,614871,614872,614905,614906,614907,614908,614909,614910,614911,614912,614913,614914,614915,614916,614917,614918,614919,614920,614921,614922,614923,614924,614925,614926,614927,614928,614929,614930,614933,614947,614949,614950,614951,614953,614955,614956,614957,614961,614962,614964,614965,614970,614971,614973,614975,614977,614984,614996,614997,615000,615002,615003,615004,615005,615006,615007,615008,615009,615598,615602,615609,616656,616657,616658,616659,616660,616661,616662,616663,616664,616665,616666,616667,616668,616669,616670,616678,616690,616692,616693,616694,616697,616702,616703,617015,617016,617019,617021,617023,617024,617027,617031,617033,617035,617038,617039,617042,617044,617047,617050,617053,617054,617055,617056,617057,617061,617069,617074,617076,617085,617087,617088,617090,617091,617093,617107,617108,617114,617118,617119,617126,617133,617240,617245,617249,617250,617252,617253,617254,617255,617257,617260,617261,617262,617263,617265,617267,617268,617269,617271,617272,617273,617281,617285,617286,617289,617291,617298,617299,617300,617301,617302,617303,617304,617305,617306,617307,617308,617309,617310,617311,617312,617316,617317,617318,617321,617392,618375,618377,618378,618379,618380,618382,618383,618384,618385,618389,618392,618395,618396,618397,618398,618401,618402,618403,618409,618412,618415,618417,618419,618430,618439,618444,618454,618455,618456,618463,618480,618490,618508,618515,618526,618534,618541,618553,618643,618765,618806,619450,619452,619453,619457,619459,619464,619465,619466,619469,619473,619475,619478,619484,619486,619489,619495,619496,619497,619498,619499,619500,619502,619504,619505,619524,619536,619566,619567,619579,619587,619593,619599,619601,619603,619605,619606,619607,619608,619609,619610,619611,619612,619613,619614,619615,619616,619617,619618,619619,619620,619621,619622,619623,619624,619625,619635,619637,619638,619642,619643,619644,619645,619927,619930,619931,619934,619935,619936,619939,619940,619941,619947,619950,619951,619953,619954,619956,620091,620110,620111,620125,620142,620143,620148,620152,620156,620163,620165,620167,620189,620324,620326,620334,620341,620343,620349,620353,620359,620362,620372,620375,620379,620381,620387,620388,620402,620406,620407,620409,620411,620413,620420,620422,620424,620427,620431,620436,620437,620440,620442,620446,620454,620457,620458,620459,620461,620462,620466,620473,620476,620481,620486,620494,620528,620541,620548,620555,620560,620569,620571,620574,620578,620610,620621,620645,620706,620709,620717,620727,620730,620732,620735,620737,620741,620744,620747,620753,620754,620755,620771,620781,620786,620789,620802,620870,620876,620880,620881,620885,620886,620887,620888,620889,620891,621078,621091,621110,621116,621129,621139,621142,621146,621148,621152,621157,621159,621168,621173,621176,621177,621179,621190,621194,621199,621202,621206,621210,621212,621272,621274,621278,621282,621289,621295,621304,621308,621403,621409,621411,621413,621415,621416,621417,621418,621419,621420,621421,621422,621423,621424,621425,621426,621947,621949,621951,621953,621957,623068,623073,623076,623079,623107,623111,623112,623113,623122,623268,623270,623274,623279,623280,623284,623291,623304,623305,623316,623321,623519,623520,623523,623526,623527,623529,623531,623535,623538,623539,623547,623548,623550,623551,623552,623570,623573,623580,623583,623586,623587,623589,623590,623591,623594,623597,623598,623599,623600,623628,623634,623637,623638,623639,623641,623642,623643,623645,623647,623648,623649,623650,623651,623840,623842,623848,623861,623870,623879,623880,623888,623889,623891,623893,623895,623897,623898,623899,623900,623902,623904,623906,623907,623909,623910,623911,623912,623913,623914,623915,623917,623918,623919,623920,623921,623922,623924,623925,623926,623929,623930,623931,623932,623933,623934,623935,623936,623937,623938,623939,623940,623941,623942,623943,623944,623945,623946,623947,623948,623949,623950,623951,623952,623953,623954,623955,623956,623957,623958,623959,623960,623961,623962,623963,623964,623965,623966,623967,623969,623970,623971,623972,623973,623974,623975,623976,623977,623978,623980,623982,623984,623985,623986,623987,623989,623990,623993,623994,623995,623996,624000,624001,624002,624004,624006,624007,624008,624009,624011,624012,624013,624014,624016,624017,624019,624020,624021,624023,624024,624025,624026,624028,624029,624031,624034,624037,624038,624041,624042,624051,624052,624055,624056,624057,624058,624060,624062,624064,624065,624066,624068,624070,624072,624074,624076,624077,624079,624081,624082,624085,624086,624087,624090,624091,624092,624094,624095,624097,624101,624104,624106,624109,624112,624113,624272,624273,624276,624278,624280,624324,624327,624328,624344,624350,624357,624367,624369,624370,624376,624378,624379,624381,624384,624386,624390,624392,624414,624416,624418,624420,624421,624422,624424,624425,624426,624427,624430,624432,624438,624439,624441,624445,624446,624449,624450,624451,624452,624453,624455,624456,624457,624458,624459,624460,624461,624462,624463,624464,624465,624467,624468,624469,624470,624471,624472,624473,624474,624475,624476,624478,624479,624480,624481,624482,624483,624484,624485,624486,624487,624488,624489,624490,624491,624492,624494,624495,624496,624497,624498,624500,624501,624502,624503,624505,624506,624507,624508,624509,624510,624511,624513,624514,624515,624517,624523,624526,624527,624530,624533,624537,624539,624541,624542,624543,624544,624547,624550,624553,624557,624566,624567,624572,624573,624583,624588,624592,624593,624595,624596,624598,624601,624603,624604,624605,624606,624607,624608,624609,624611,624613,624616,624618,624620,624621,624626,624627,624628,625042,625046,625047,625048,625050,625051,625053,625054,625057,625059,625060,625191,625192,625195,625199,625202,625207,625208,625223,625225,625229,625231,625232,625236,625237,625239,625240,625241,625243,625244,625246,625247,625248,625249,625251,625252,625254,625255,625256,625258,625259,625268,625270,625276,625277,625278,625279,625280,625281,625324,625325,625330,625333,625335,625337,625342,625349,625550,625553,625561,625582,625586,625588,625589,625591,625593,625602,625606,625608,625613,625616,625617,625621,625623,625625,625626,625629,668415,668416,668417,668418,668422,668426,668428,668437,668446,669024,669030,669038,669150,669198,669204,669210,704270,704289,704291,704296,704300,704305,704325,704327,704328,704331,704333,704335,704338,704342,704347,704350,704351,704356,704361,704367,704374,704376,704380,704382,704385,704398,704404,704409,704413,704416,704418,704419,704424,704433,704437,704452,704458,704481,704497,704499,704641,704648,704654,704658,704667,704882,704886,704888,704892,704893,704894,705088,705108,705128,705131,705133,705139,705146,705148,705150,705155,705156,705159,705161,705164,705169,705172,705174,705177,705183,705184,705185,705188,705190,705191,705193,705195,705201,705212,705217,705219,705223,705227,705229,705647,705648,705649,706146,706147,706148,706150,720069,720070,720071,720072,720073,720074,720075,720079,720080,720082,720083,720084,720085,720086,720087,720088,720089,720090,720091,720092,720093,720094,720095,720096,720097,720098,720099,720100,720101,720102,720103,720104,720105,720106,720107,720108,720109,720110,720111,720112,720113,720114,720115,720116,720117,720119,720120,720123,720125,720126,720131,720132,720136,720138,759826,759867,759870,759881,759916,759919,759924,759930,759933,759937,759944,760024,760028,760034,760073,760142,760153,760226,760230,760235,760293,760349,760350,760353,760354,760357,760359,760361,760368,760374,760378,760386,760389,760400,760404,761026,761633,761636,761642,761644,761645,761646,761647,761648,761649,761652,761654,761656,761657,761658,761659,761660,761661,761662,761663,761664,761665,761682,761684,761687,761688,761692,761700,761721,761723,761742,761749,762020,762021,762022,762023,762035,762056,762057,762060,762062,762207,762208,762209,762210,762211,762212,762213,762215,762216,762218,762219,762220,762221,762222,762223,762225,762226,762228,762229,762230,762231,762232,762233,762235,762237,762238,762239,762240,762241,762243,762244,762245,762246,762247,762248,762249,762250,762251,762253,762254,762255,762281,762282,762290,762291,762298,762305,762309,762310,762326,762367,762371,770122,770123,770125,770127,770129,770130,770131,770132,770134,770136,770137,770138,770144,770146,770147,770149,770151,770152,770153,770154,770155,770156,770157,770158,770159,770161,770175,770247,770255,770258,770278,770280,770291,770292,770293,770296,770297,773396,773397,773401,773402,773404,773406,773407,773409,773410,773411,773412,773413,773414,773424,773425,773426,773427,790758,790761,790765,790768,790770,790771,790772,790773,790775,790776,790782,790783,790785,790793,790807,790829,790971,791116,791121,791123,791125,791130,791132,791133,791135,791136,791137,791138,791139,791140,791141,791143,791144,791145,791147,791148,791149,791150,791152,791154,791156,791160,791161,791162,791163,791164,791167,791168,791169,791170,791171,791173,791178,791179,791181,791183,791184,791185,791259,791261,791262,791270,791271,791272,791274,791275,791277,791278,791280,791283,791284,791286,791287,791288,791290,791292,791295,791298,791301,791303,791306,791308);
$cnt = 0;
foreach ($raceIds as $raceId) {

	$cnt++;

	echo $cnt . '.';
	
	$stmt = $pdo->query("SELECT player_id, map_id, server_id, time, created FROM race WHERE id = $raceId LIMIT 1;");
	if (!$delRace = $stmt->fetchObject()) {
	
		continue;
	}

	/*
	* insert deleted race into delete_races table
	*/
	$pdo->query("INSERT INTO `deleted_race` SELECT * FROM race WHERE id = $raceId LIMIT 1;");
	echo '.';
	
	$pdo->query("UPDATE player SET races = races-1 WHERE id = $delRace->player_id LIMIT 1");
	echo '.';
	$pdo->query("UPDATE map SET races = races-1 WHERE id = $delRace->map_id");
	echo '.';
	$pdo->query("UPDATE gameserver SET races = races-1 WHERE id = $delRace->server_id");
	echo '.';
	$pdo->query("DELETE FROM race WHERE id = $raceId LIMIT 1;");
	echo '.';
	$stmt = $pdo->query("SELECT *
		FROM race
		WHERE map_id = $delRace->map_id
		  AND player_id = $delRace->player_id
		ORDER BY time ASC
		LIMIT 1;");
	echo '.';
	if ($bestRace = $stmt->fetchObject()) {
	
		$pdo->query("UPDATE `player_map`
		SET `time` = $bestRace->time,
			`prejumped` = '$bestRace->prejumped',
			`server_id` = $bestRace->server_id,
			`created` = '$bestRace->created',
			`tries` = (SELECT SUM(`tries`) FROM `race` WHERE `player_id` = $delRace->player_id AND `map_id` = $delRace->map_id AND `tries` IS NOT NULL),
			`duration` = (SELECT SUM(`duration`) FROM `race` WHERE `player_id` = $delRace->player_id AND `map_id` = $delRace->map_id AND `duration` IS NOT NULL)
		WHERE map_id = $delRace->map_id
		  AND player_id = $delRace->player_id
		LIMIT 1;");
		echo '.';
		
	} else {
	
		$pdo->query("DELETE FROM player_map WHERE player_id = $delRace->player_id AND map_id = $delRace->map_id LIMIT 1;");
		echo '.';
	}
	
	
	$stmt = $pdo->query("SELECT `p`.`id`, `pm`.`time`, `p`.`name`, `pm`.`races`, `pm`.`playtime`, `pm`.`created`, `pm`.`prejumped`, `pm`.`points`, `pm`.`map_id`, `pm`.`player_id`
		FROM `player_map` `pm`
		INNER JOIN `player` `p` ON `p`.`id` = `pm`.`player_id`
		WHERE `pm`.`time` IS NOT NULL
		  AND `pm`.`time` > 0
		  AND `pm`.`map_id` = $delRace->map_id
		  AND `pm`.`prejumped` in ('true', 'false')
		ORDER BY `pm`.`time` ASC;");
	echo '.';

	$bestTime = 0;
	$lastRaceTime = 0;
	$offset = 0;
	$cleanOffset = 0;
	$lastCleanRaceTime = 0;
	$currentPosition = 0;
	$currentCleanPosition = 0;
	$realPosition = 0;
	$maxPositions = 30;
	$affectedPlayerIds = array();
	
	while($personalRecord = $stmt->fetchObject()) {

		if ($bestTime == 0)
			$bestTime = $personalRecord->time;

		if ($personalRecord->time == $lastRaceTime)
			$offset++;
		else
			$offset = 0;

		if ( $personalRecord->prejumped == 'false' && $personalRecord->time == $lastCleanRaceTime )
			$cleanOffset++;
		else
			$cleanOffset = 0;

		$currentPosition++;
		if ( $personalRecord->prejumped == 'false' )
		{
			$currentCleanPosition++;
			$realPosition = $currentCleanPosition - $cleanOffset;
			$lastCleanRaceTime = $personalRecord->time;
		}
		else
		{
			$realPosition = $currentPosition - $offset;
		}

		$points = ($maxPositions + 1) - $realPosition;
		switch ($realPosition)
		{
			case 1:
				$points += 10;
				break;
			case 2:
				$points += 5;
				break;
			case 3:
				$points += 3;
				break;
		}

		$points = $points > 0 ? $points : 0;
		
		$lastRaceTime = $personalRecord->time;
		
		//only update points for players whose points have changed
		if ( $personalRecord->points != $points )
		{
			// set points in player_map
			$pdo->query("UPDATE `player_map` SET `points` = $points WHERE `map_id` = $personalRecord->map_id AND `player_id` = $personalRecord->player_id;");
			echo '.';
			$affectedPlayerIds[] = $personalRecord->player_id;
		}	
	}
	
	if (count($affectedPlayerIds)) {
	
		$pdo->query("UPDATE `player` SET `points` = (SELECT SUM(`points`) FROM `player_map` WHERE `player_id` = `player`.`id`), `diff_points` = (`points` - (SELECT `points` FROM `player_history` WHERE `player_id` = `player`.`id` ORDER BY `date` DESC LIMIT 1)) WHERE `id` IN(". join(',', $affectedPlayerIds) .");");
		echo '.';
	}
}