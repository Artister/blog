CREATE TABLE blog.`User` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username_UNIQUE` (`Username`)
);
CREATE TABLE blog.`Author` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  UNIQUE KEY `UserId_UNIQUE` (`UserId`),
  KEY `UserFk_idx` (`UserId`),
  CONSTRAINT `AuthorUser` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`)
);
CREATE TABLE blog.`Section` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Title` varchar(60) DEFAULT NULL,
  `Slug` varchar(60) DEFAULT NULL,
  `Image` varchar(60) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  UNIQUE KEY `Slug_UNIQUE` (`Slug`)
);
CREATE TABLE blog.`Post` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `SectionId` int NOT NULL,
  `AuthorId` int NOT NULL,
  `Slug` varchar(45) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Excerpt` varchar(255) DEFAULT NULL,
  `Image` varchar(45) DEFAULT NULL,
  `Content` text,
  `CreatedAt` timestamp NULL DEFAULT NULL,
  `EditedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `idPost_UNIQUE` (`Id`),
  KEY `SectionId_idx` (`SectionId`),
  KEY `AuthorFk_idx` (`AuthorId`),
  CONSTRAINT `PostAuthor` FOREIGN KEY (`AuthorId`) REFERENCES `Author` (`Id`),
  CONSTRAINT `PostSection` FOREIGN KEY (`SectionId`) REFERENCES `Section` (`Id`)
);
CREATE TABLE blog.`Comment` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `PostId` int NOT NULL,
  `AuthorId` int DEFAULT NULL,
  `Content` text,
  `CreatedAt` timestamp NULL DEFAULT NULL,
  `EditedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  KEY `PostFk_idx` (`PostId`),
  KEY `AuthorFk_idx` (`AuthorId`),
  CONSTRAINT `CommentAuthor` FOREIGN KEY (`AuthorId`) REFERENCES `Author` (`Id`),
  CONSTRAINT `CommentPost` FOREIGN KEY (`PostId`) REFERENCES `Post` (`Id`)
);
  