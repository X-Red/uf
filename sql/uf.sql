--
-- Table structure for table `uf`
--

CREATE TABLE IF NOT EXISTS `uf` (
  `uf_fecha` date NOT NULL,
  `uf_valor` float(10,2) NOT NULL,
  `uf_fechaact` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uf`
--
ALTER TABLE `uf`
 ADD PRIMARY KEY (`uf_fecha`);
