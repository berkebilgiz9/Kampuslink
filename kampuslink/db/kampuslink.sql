--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2025-05-16 15:52:05

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6 (class 2615 OID 16552)
-- Name: ogrencitoplulukbilgilendirme; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA ogrencitoplulukbilgilendirme;


ALTER SCHEMA ogrencitoplulukbilgilendirme OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 223 (class 1259 OID 16697)
-- Name: clubs; Type: TABLE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE TABLE ogrencitoplulukbilgilendirme.clubs (
    id integer NOT NULL,
    club_name character varying(100) NOT NULL,
    faculty_name character varying(100) NOT NULL
);


ALTER TABLE ogrencitoplulukbilgilendirme.clubs OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16696)
-- Name: clubs_id_seq; Type: SEQUENCE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE SEQUENCE ogrencitoplulukbilgilendirme.clubs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE ogrencitoplulukbilgilendirme.clubs_id_seq OWNER TO postgres;

--
-- TOC entry 4868 (class 0 OID 0)
-- Dependencies: 222
-- Name: clubs_id_seq; Type: SEQUENCE OWNED BY; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER SEQUENCE ogrencitoplulukbilgilendirme.clubs_id_seq OWNED BY ogrencitoplulukbilgilendirme.clubs.id;


--
-- TOC entry 221 (class 1259 OID 16676)
-- Name: events; Type: TABLE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE TABLE ogrencitoplulukbilgilendirme.events (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    event_date date NOT NULL,
    location character varying(255) NOT NULL,
    created_by integer,
    club_id integer,
    file_path text
);


ALTER TABLE ogrencitoplulukbilgilendirme.events OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16675)
-- Name: events_id_seq; Type: SEQUENCE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE SEQUENCE ogrencitoplulukbilgilendirme.events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE ogrencitoplulukbilgilendirme.events_id_seq OWNER TO postgres;

--
-- TOC entry 4869 (class 0 OID 0)
-- Dependencies: 220
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER SEQUENCE ogrencitoplulukbilgilendirme.events_id_seq OWNED BY ogrencitoplulukbilgilendirme.events.id;


--
-- TOC entry 234 (class 1259 OID 17084)
-- Name: roles; Type: TABLE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE TABLE ogrencitoplulukbilgilendirme.roles (
    id integer NOT NULL,
    role_name character varying(50) NOT NULL
);


ALTER TABLE ogrencitoplulukbilgilendirme.roles OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 17083)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE SEQUENCE ogrencitoplulukbilgilendirme.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE ogrencitoplulukbilgilendirme.roles_id_seq OWNER TO postgres;

--
-- TOC entry 4870 (class 0 OID 0)
-- Dependencies: 233
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER SEQUENCE ogrencitoplulukbilgilendirme.roles_id_seq OWNED BY ogrencitoplulukbilgilendirme.roles.id;


--
-- TOC entry 232 (class 1259 OID 17058)
-- Name: user_club_memberships; Type: TABLE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE TABLE ogrencitoplulukbilgilendirme.user_club_memberships (
    user_id integer NOT NULL,
    club_id integer NOT NULL,
    role_id integer
);


ALTER TABLE ogrencitoplulukbilgilendirme.user_club_memberships OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16592)
-- Name: users; Type: TABLE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE TABLE ogrencitoplulukbilgilendirme.users (
    id integer NOT NULL,
    first_name character varying(100),
    last_name character varying(100),
    email character varying(100),
    password character varying(255),
    club_id integer,
    is_approved integer DEFAULT 0,
    is_admin integer DEFAULT 0,
    role character varying(50) DEFAULT 'öğrenci'::character varying
);


ALTER TABLE ogrencitoplulukbilgilendirme.users OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16591)
-- Name: users_id_seq; Type: SEQUENCE; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

CREATE SEQUENCE ogrencitoplulukbilgilendirme.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE ogrencitoplulukbilgilendirme.users_id_seq OWNER TO postgres;

--
-- TOC entry 4871 (class 0 OID 0)
-- Dependencies: 218
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER SEQUENCE ogrencitoplulukbilgilendirme.users_id_seq OWNED BY ogrencitoplulukbilgilendirme.users.id;


--
-- TOC entry 4682 (class 2604 OID 17061)
-- Name: clubs id; Type: DEFAULT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.clubs ALTER COLUMN id SET DEFAULT nextval('ogrencitoplulukbilgilendirme.clubs_id_seq'::regclass);


--
-- TOC entry 4681 (class 2604 OID 17062)
-- Name: events id; Type: DEFAULT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.events ALTER COLUMN id SET DEFAULT nextval('ogrencitoplulukbilgilendirme.events_id_seq'::regclass);


--
-- TOC entry 4683 (class 2604 OID 17087)
-- Name: roles id; Type: DEFAULT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.roles ALTER COLUMN id SET DEFAULT nextval('ogrencitoplulukbilgilendirme.roles_id_seq'::regclass);


--
-- TOC entry 4677 (class 2604 OID 17063)
-- Name: users id; Type: DEFAULT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.users ALTER COLUMN id SET DEFAULT nextval('ogrencitoplulukbilgilendirme.users_id_seq'::regclass);


--
-- TOC entry 4859 (class 0 OID 16697)
-- Dependencies: 223
-- Data for Name: clubs; Type: TABLE DATA; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

COPY ogrencitoplulukbilgilendirme.clubs (id, club_name, faculty_name) FROM stdin;
1	A.Erdoğan Sağlık Hizmetleri Topluluğu	A.Erdoğan SHMYO
2	Yaşlı Bakım Hizmetlerini Geliştirme Topluluğu	A.Erdoğan SHMYO
3	Endüstri ve Otomasyon Topluluğu	Alaplı MYO
4	Alaplı MYO Spor Topluluğu	Alaplı MYO
5	Gezi Topluluğu	Alaplı MYO
6	Müzik ve Sanat Topluluğu	Alaplı MYO
7	Sualtı Sporları Topluluğu	B.E.S.Y.O.
8	Doğa Sporları Topluluğu (KARADOST)	B.E.S.Y.O.
9	Tenis Topluluğu	B.E.S.Y.O.
10	Veteriner Sağlık Topluluğu (VEST)	Çaycuma Gıda ve Tarım M.Y.O.
11	Kimya Teknolojisi Topluluğu	Çaycuma Gıda ve Tarım M.Y.O.
12	Gıda Topluluğu	Çaycuma Gıda ve Tarım M.Y.O.
13	Ahşap Kültürü ve Doğa Topluluğu	Çaycuma M.Y.O.
14	Kariyer ve Gelişim Topluluğu	Çaycuma M.Y.O.
15	Sivil Havacılık Topluluğu	Çaycuma M.Y.O.
16	Denizcilik Topluluğu	Denizcilik Fak.
17	Tiyatro Topluluğu	Devlet Konservatuarı
18	Organoloji Topluluğu	Devlet Konservatuarı
19	Klasik Müzik Topluluğu	Devlet Konservatuarı
20	Türk Halk Müziği Araştırma ve Uygulama	Devlet Konservatuarı
21	Gastronomi Topl	Devrek M.Y.O.
22	Tiyatro Müzik ve Şiir Topluluğu	Devrek M.Y.O.
23	Turizm Tanıtma ve Gezi Topl.	Devrek M.Y.O.
24	Doğa ve Sevimli Patiler Topluluğu	Devrek M.Y.O.
25	Adalet Öğrenci Topluluğu	Devrek M.Y.O.
26	Spor Topluluğu	Devrek Uyg. Bil. Yük.Ok.
27	Finans Topluluğu	Devrek Uyg. Bil. Yük.Ok.
28	Diş Hekimliği Fakültesi Bilimsel Araştırma	Diş Hekimliği Fakültesi
29	Diş Hekimliği Fakültesi Müzik Topluluğu	Diş Hekimliği Fakültesi
30	Farmakademi Topluluğu	Eczacılık Fakültesi
31	Spor Eczacılığı Topluluğu	Eczacılık Fakültesi
32	Erasmus Topluluğu	ERASMUS + Değişim Programı Koord.
33	Satranç Topluluğu	Ereğli Eğitim Fakültesi
34	Genç Kalemler Edebiyat Topluluğu	Ereğli Eğitim Fakültesi
35	Matematik Eğitimi Topluluğu	Ereğli Eğitim Fakültesi
36	Psikolojik Danışma ve Rehberlik Topl.	Ereğli Eğitim Fakültesi
37	Sınıf Eğitimi Topluluğu	Ereğli Eğitim Fakültesi
38	Okuma Kültürü Topluluğu	Ereğli Eğitim Fakültesi
39	Geleneksel Türk Okçuluğu Topluluğu	Ereğli Eğitim Fakültesi
40	Sınıf Öğretmenliği Topluluğu	Ereğli Eğitim Fakültesi
41	Türk Dili ve Dünyası Topluluğu	Ereğli Eğitim Fakültesi
42	Bilim ve Teknoloji Topluluğu	Ereğli Eğitim Fakültesi
43	Ahbap Topluluğu	Ereğli Eğitim Fakültesi
44	Kimya Topluluğu	Fen Fakültesi
45	MoleBiogenetik Topluluğu	Fen Fakültesi
46	Motosiklet Topluluğu	Fen Fakültesi
47	Amatör Astronomi Topluluğu	Fen Fakültesi
48	Biyoloji Topluluğu	Fen Fakültesi
49	Matematik Topluluğu	Fen Fakültesi
50	Bilardo Topluluğu	Fen Fakültesi
51	Çinicilik ve Seramik Sanatları Topluluğu	Gökçebey MMCMYO
52	Azerbaycan Kültür Topluluğu	Gökçebey MMCMYO
53	Plastik Sanatlar Topluluğu	Güz.Sant.Fak.
54	Görsel Sanatlar ve Tasarım Topl.	Güz.Sant.Fak.
55	İşletme Topluluğu	İktisadi ve İdari Bil.Fak.
56	Maliye Topluluğu	İktisadi ve İdari Bil.Fak.
57	İktisat Topluluğu	İktisadi ve İdari Bil.Fak.
58	Girişimcilik ve Ticaret Topluluğu	İktisadi ve İdari Bil.Fak.
59	Kızılay Gençlik Topluluğu	İktisadi ve İdari Bil.Fak.
60	Atılım ve Kariyer Topluluğu	İktisadi ve İdari Bil.Fak.
61	Ombudsman Topluluğu	İktisadi ve İdari Bil.Fak.
62	Toplum Gönüllüleri Topluluğu	İktisadi ve İdari Bil.Fak.
63	Sosyal Politika Topluluğu	İktisadi ve İdari Bil.Fak.
64	Psikoloji Eğitim ve Doğa Topluluğu	İktisadi ve İdari Bil.Fak.
65	Üniversite Aktivite Topluluğu	İktisadi ve İdari Bil.Fak.
66	Sosyoloji Topluluğu	İktisadi ve İdari Bil.Fak.
67	Kültür ve Sosyal Araştırmalar Topl.	İktisadi ve İdari Bil.Fak.
68	Öğrenci Konseyi Topluluğu	İktisadi ve İdari Bil.Fak.
69	Yeniler Topluluğu	İlahiyat Fak.
70	Genç Kadem Topluluğu	İlahiyat Fak.
71	Genç Gönüllüler Topluluğu	İlahiyat Fak.
72	Ahde Vefa Topluluğu	İlahiyat Fak.
73	Kardeşlik Topluluğu	İlahiyat Fak.
74	İnsani Değerler Topluluğu	İlahiyat Fak.
75	Geçerken Topluluğu	İlahiyat Fak.
76	Katre-i Takva Topluluğu	İlahiyat Fak.
77	İletişim Topluluğu	İletişim Fakültesi
78	Yönetim ve Siyaset Topluluğu	İletişim Fakültesi
79	Sivil Yaşam Topluluğu	İletişim Fakültesi
80	Dezenformasyonla Mücadele Topluluğu	İletişim Fakültesi
81	Karaelmas Sinema Topluluğu	İletişim Fakültesi
82	Fotoğraf ve Video Topluluğu	İletişim Fakültesi
83	Tarih Topluluğu	İnsan ve Toplum Bilimleri Fak.
169	Genç Yeşilay Topluluğu	İnsan ve Toplum Bilimleri Fak.
170	Psikoloji ve Yaşam Topluluğu	İnsan ve Toplum Bilimleri Fak.
172	Arkeoloji Topluluğu	İnsan ve Toplum Bilimleri Fak.
173	Türk Kültürünü Tanıtma Topluluğu	İnsan ve Toplum Bilimleri Fak.
174	Karaelmas Kitap Topluluğu	İnsan ve Toplum Bilimleri Fak.
175	Merkez Kampüs Amatör Tiyatro Topl.	İnsan ve Toplum Bilimleri Fak.
176	Mağara Araştırma Topluluğu	İnsan ve Toplum Bilimleri Fak.
177	Batı Kültür ve Edebiyatları Topluluğu	İnsan ve Toplum Bilimleri Fak.
178	Karaelmas Arama Kurtarma Topluluğu	İnsan ve Toplum Bilimleri Fak.
179	Psikoloji Akademik Araştırma Topluluğu(PAAT)	İnsan ve Toplum Bilimleri Fak.
180	Ateş Böceği Topluluğu	İnsan ve Toplum Bilimleri Fak.
181	Yüksek Sanat Topluluğu	İnsan ve Toplum Bilimleri Fak.
182	Türkoloji Topluluğu	İnsan ve Toplum Bilimleri Fak.
183	Kadın Çalışmaları Öğrenci Topluluğu	İnsan ve Toplum Bilimleri Fak.
184	Karaelmas Enerji (Enerjikol) Öğrenci Topl.	Kdz. Ereğli M.Y.O.
185	Genç Kariyer Planlama ve Bilişim Topluluğu	Kdz. Ereğli M.Y.O.
186	Karaelmas Alemdar Oryantiring Takımı	Kdz. Ereğli M.Y.O.
187	Karaelmas Yapay Zeka ve Derin Öğrenme	Kdz. Ereğli M.Y.O.
188	Kdz. Ereğli Turizm Topluluğu	Kdz.Ereğli Turizm Fak.
189	Genç Tema Topluluğu	Mühendislik Fak.
190	Çevre Topluluğu	Mühendislik Fak.
191	IEEE Topluluğu	Mühendislik Fak.
192	Malzeme Bilimi Topluluğu	Mühendislik Fak.
193	Gıda Mühendisliği Topluluğu	Mühendislik Fak.
194	Kontrol ve Otomasyon Topluluğu	Mühendislik Fak.
195	Geomatik Öğrenci Topluluğu	Mühendislik Fak.
196	Genç Madenci Öğrenci Topluluğu	Mühendislik Fak.
197	Karaelmas Yapay Zeka Topluluğu	Mühendislik Fak.
198	Makine Mühendisliği Öğrenci Topluluğu	Mühendislik Fak.
199	Akademik Düşünce Eğitim ve Medeniyet	Mühendislik Fak.
200	İnşaat Mühendisliği Topluluğu	Mühendislik Fak.
201	Teknoloji Topluluğu	Mühendislik Fak.
202	Atatürkçü Düşünce Topluluğu	Mühendislik Fak.
203	Genç Doğa Bilimcileri Topluluğu	Mühendislik Fak.
204	Müzik Topluluğu	Mühendislik Fak.
206	Sıfır Atık Öğrenci Topluluğu	Mühendislik Fak.
207	Afrika Kültür Öğrenci Topluluğu (Afrozon)	Mühendislik Fak.
208	Google Developer Groups on Campus	Mühendislik Fak.
209	Siber Güvenlik Topluluğu	Mühendislik Fak.
210	Havacılık ve Uzay Topluluğu	Mühendislik Fak.
211	Halk Oyunları Topluluğu	Mühendislik Fak.
212	Sağlık Topluluğu	Sağlık Bilimleri Fak.
213	Lösemili Çocuklara Fayda Topl.	Sağlık Bilimleri Fak.
214	Kadın ve Adalet Topluluğu	Sağlık Bilimleri Fak.
215	Fizyoterapi ve Rehabilitasyon Topl.	Sağlık Bilimleri Fak.
216	Sosyal Hizmet Topluluğu	Sağlık Bilimleri Fak.
217	Mavi Kelebekler Çocuk Hakları Topl.	Sağlık Bilimleri Fak.
218	Turk MSIC Tıp Öğrencileri Topluluğu	Tıp Fakültesi
219	SoundDrug Müzik Topluluğu	Tıp Fakültesi
220	Gönüllü Hekim Adayları Topluluğu	Tıp Fakültesi
221	Zonguldak EMSA Topluluğu	Tıp Fakültesi
222	Tıp Fakültesi Değişim Öğrencileri Topluluğu	Tıp Fakültesi
223	Tıp Satranç ve Zeka Topluluğu	Tıp Fakültesi
224	Tıbbi Araştırmalar Topluluğu (BETAT)	Tıp Fakültesi
225	Davranış ve Beyin Araştırma Topluluğu	Tıp Fakültesi
226	Hekim ve Liderlik Topluluğu	Tıp Fakültesi
227	İncirharmanı Kampüsü Sosyal Etkinlik	Yab.Dil.Yük.Ok.
228	Egather - İngilizce Sosyalleşme Topluluğu	Yab.Dil.Yük.Ok.
229	Teknik Bilimler Topluluğu	Zong. M.Y.O.
230	Turizm Topluluğu	Zong. M.Y.O.
231	Spor ve Sahne Topluluğu	Zong. M.Y.O.
\.


--
-- TOC entry 4857 (class 0 OID 16676)
-- Dependencies: 221
-- Data for Name: events; Type: TABLE DATA; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

COPY ogrencitoplulukbilgilendirme.events (id, title, description, event_date, location, created_by, club_id, file_path) FROM stdin;
12	Organik Kimya	organik kimya bileşenleri tanıtımı	2025-06-09	Farabi Kampüsü	20	44	\N
10	Siber Saldırıları Engelleme	siber saldırılarla alakalı etkinlik	2025-08-25	Farabi Kampüsü	14	209	\N
11	Arkeoloji	arkeolojik madenleri inceliyoruz	2025-05-09	Farabi Kampüsü	20	172	\N
13	Biyoloji	biyolojik maddelerin tanıtımı	2025-06-09	İbni Sina 	14	48	\N
14	Yapay Zeka	yapay zekanın doğru kullanımı	2025-05-25	Farabi Kampüsü	14	197	\N
15	Kayaçlar	kayaçları inceliyoruz	2025-05-15	İbni Sina	20	\N	\N
16	Fotoğrafçılık	güzel fotoğraf çekme etkinliği	2025-05-23	Farabi Kampüsü	20	\N	\N
\.


--
-- TOC entry 4862 (class 0 OID 17084)
-- Dependencies: 234
-- Data for Name: roles; Type: TABLE DATA; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

COPY ogrencitoplulukbilgilendirme.roles (id, role_name) FROM stdin;
1	öğrenci
2	görevli
3	admin
\.


--
-- TOC entry 4860 (class 0 OID 17058)
-- Dependencies: 232
-- Data for Name: user_club_memberships; Type: TABLE DATA; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

COPY ogrencitoplulukbilgilendirme.user_club_memberships (user_id, club_id, role_id) FROM stdin;
20	44	2
20	172	1
13	54	1
21	15	2
13	188	1
\.


--
-- TOC entry 4855 (class 0 OID 16592)
-- Dependencies: 219
-- Data for Name: users; Type: TABLE DATA; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

COPY ogrencitoplulukbilgilendirme.users (id, first_name, last_name, email, password, club_id, is_approved, is_admin, role) FROM stdin;
11	Murat	Kalın	murat1@gmail.com	$2y$10$SIDvfnxxyz0rwPw5olMBweI7tDtR76rr7FdnVGn1tJ0u6vAST4YDe	\N	1	0	öğrenci
12	Mert	Korkmaz	mert2@gmail.com	$2y$10$HUVvbsqUtuMFSJYSDIavS.AQthijIH6r8whlf4rV6Ojdsy.qAcKEO	\N	1	0	öğrenci
14	Mehmet	Donuk	mehmet1@gmail.com	$2y$10$qnNc1WpK9rGJQR4Id2l5ZurEx7yZ30HH6VD9fhgtEOAjkQvp0B47y	\N	1	0	görevli
20	mehmet	odun	mehmet2@gmail.com	$2y$10$4LqcSH93lr0bvtpWf3E3.OT9O3CdCaXGI88LGZkBYpllBC1C5mdg.	\N	1	0	görevli
13	Berke	Bilgiz	admin@gmail.com	$2y$10$pbUD8RA53/d9q92toOWnJORiGc2sP.wluoBlmyD6px3.QIn2AOrcS	\N	1	1	admin
21	Faruk	Yılmaz	faruk@gmail.com	$2y$10$C8GmAeF1SSzw5E4qdnRba.qaroFrh2g5BrOCXHBE6FJfXGn5h9jCK	\N	1	0	görevli
\.


--
-- TOC entry 4872 (class 0 OID 0)
-- Dependencies: 222
-- Name: clubs_id_seq; Type: SEQUENCE SET; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

SELECT pg_catalog.setval('ogrencitoplulukbilgilendirme.clubs_id_seq', 231, true);


--
-- TOC entry 4873 (class 0 OID 0)
-- Dependencies: 220
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

SELECT pg_catalog.setval('ogrencitoplulukbilgilendirme.events_id_seq', 12, true);


--
-- TOC entry 4874 (class 0 OID 0)
-- Dependencies: 233
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

SELECT pg_catalog.setval('ogrencitoplulukbilgilendirme.roles_id_seq', 2, true);


--
-- TOC entry 4875 (class 0 OID 0)
-- Dependencies: 218
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

SELECT pg_catalog.setval('ogrencitoplulukbilgilendirme.users_id_seq', 21, true);


--
-- TOC entry 4691 (class 2606 OID 16702)
-- Name: clubs clubs_pkey; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.clubs
    ADD CONSTRAINT clubs_pkey PRIMARY KEY (id);


--
-- TOC entry 4689 (class 2606 OID 16683)
-- Name: events events_pkey; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- TOC entry 4699 (class 2606 OID 17089)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 4701 (class 2606 OID 17091)
-- Name: roles roles_role_name_key; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.roles
    ADD CONSTRAINT roles_role_name_key UNIQUE (role_name);


--
-- TOC entry 4693 (class 2606 OID 17077)
-- Name: clubs unique_club_name; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.clubs
    ADD CONSTRAINT unique_club_name UNIQUE (club_name);


--
-- TOC entry 4695 (class 2606 OID 17065)
-- Name: user_club_memberships user_club_memberships_pkey; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.user_club_memberships
    ADD CONSTRAINT user_club_memberships_pkey PRIMARY KEY (user_id, club_id);


--
-- TOC entry 4697 (class 2606 OID 17079)
-- Name: user_club_memberships user_club_memberships_user_id_club_id_key; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.user_club_memberships
    ADD CONSTRAINT user_club_memberships_user_id_club_id_key UNIQUE (user_id, club_id);


--
-- TOC entry 4685 (class 2606 OID 16601)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 4687 (class 2606 OID 16599)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4704 (class 2606 OID 16684)
-- Name: events events_created_by_fkey; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.events
    ADD CONSTRAINT events_created_by_fkey FOREIGN KEY (created_by) REFERENCES ogrencitoplulukbilgilendirme.users(id) ON DELETE CASCADE;


--
-- TOC entry 4705 (class 2606 OID 16703)
-- Name: events fk_club; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.events
    ADD CONSTRAINT fk_club FOREIGN KEY (club_id) REFERENCES ogrencitoplulukbilgilendirme.clubs(id) ON DELETE SET NULL;


--
-- TOC entry 4702 (class 2606 OID 16778)
-- Name: users fk_club_id; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.users
    ADD CONSTRAINT fk_club_id FOREIGN KEY (club_id) REFERENCES ogrencitoplulukbilgilendirme.clubs(id);


--
-- TOC entry 4703 (class 2606 OID 16783)
-- Name: users fk_users_club_id; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.users
    ADD CONSTRAINT fk_users_club_id FOREIGN KEY (club_id) REFERENCES ogrencitoplulukbilgilendirme.clubs(id);


--
-- TOC entry 4706 (class 2606 OID 17066)
-- Name: user_club_memberships user_club_memberships_club_id_fkey; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.user_club_memberships
    ADD CONSTRAINT user_club_memberships_club_id_fkey FOREIGN KEY (club_id) REFERENCES ogrencitoplulukbilgilendirme.clubs(id) ON DELETE CASCADE;


--
-- TOC entry 4707 (class 2606 OID 17092)
-- Name: user_club_memberships user_club_memberships_role_id_fkey; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.user_club_memberships
    ADD CONSTRAINT user_club_memberships_role_id_fkey FOREIGN KEY (role_id) REFERENCES ogrencitoplulukbilgilendirme.roles(id) ON DELETE SET NULL;


--
-- TOC entry 4708 (class 2606 OID 17071)
-- Name: user_club_memberships user_club_memberships_user_id_fkey; Type: FK CONSTRAINT; Schema: ogrencitoplulukbilgilendirme; Owner: postgres
--

ALTER TABLE ONLY ogrencitoplulukbilgilendirme.user_club_memberships
    ADD CONSTRAINT user_club_memberships_user_id_fkey FOREIGN KEY (user_id) REFERENCES ogrencitoplulukbilgilendirme.users(id) ON DELETE CASCADE;


-- Completed on 2025-05-16 15:52:05

--
-- PostgreSQL database dump complete
--

