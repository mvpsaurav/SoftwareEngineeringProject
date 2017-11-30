CREATE TABLE IF NOT EXISTS `COEN174FacultyUsers` (
  `Username` varchar(50) NOT NULL,
  `HashedPassword` char(64) NOT NULL,
  `Salt` char(64) NOT NULL,
  `RealName` varchar(100) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `COEN174CourseEquivalencies` (
  `OtherCourseCode` varchar(20) NOT NULL,
  `OtherSchool` varchar(100) NOT NULL,
  `LocalCourseCode` varchar(20) NOT NULL,
  `IsApproved` tinyint(1) NOT NULL,
  `ApprovedBy` varchar(100) NOT NULL,
  `Notes` varchar(500) NOT NULL,
  PRIMARY KEY (`OtherCourseCode`,`OtherSchool`,`LocalCourseCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `COEN174FacultyUsers` (`Username`, `HashedPassword`, `Salt`, `RealName`) VALUES
('admin', 'd3d426c03232cb646c64af2e463d5816e654cb28b8b53cf5718ad67953a1afc2', '2346195101390584183492415361449784404914154212621970122758803871', 'Administrator');
