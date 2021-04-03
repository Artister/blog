CREATE TABLE blog.`User` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username_UNIQUE` (`Username`)
);
CREATE TABLE blog.`Role` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`Id`)
);
CREATE TABLE blog.`UserRole` (
  `UserId` int NOT NULL,
  `RoleId` int NOT NULL,
  PRIMARY KEY (`UserId`,`RoleId`),
  KEY `UserRole.RoleId_idx` (`RoleId`),
  CONSTRAINT `UserRole.RoleId` FOREIGN KEY (`RoleId`) REFERENCES `Role` (`Id`),
  CONSTRAINT `UserRole.UserId` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`)
);
CREATE TABLE blog.`Author` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  KEY `Author.UserId_idx` (`UserId`),
  CONSTRAINT `Author.UserId` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`)
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
  `EditedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `idPost_UNIQUE` (`Id`),
  KEY `Post.SectionId_idx` (`SectionId`),
  KEY `Post.AuthorId_idx` (`AuthorId`),
  CONSTRAINT `Post.AuthorId` FOREIGN KEY (`AuthorId`) REFERENCES `Author` (`Id`),
  CONSTRAINT `Post.SectionId` FOREIGN KEY (`SectionId`) REFERENCES `Section` (`Id`)
);
CREATE TABLE blog.`Comment` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `PostId` int NOT NULL,
  `AuthorId` int DEFAULT NULL,
  `Content` text,
  `EditedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  KEY `Comment.PostId_idx` (`PostId`),
  KEY `Comment.AuthorId_idx` (`AuthorId`),
  CONSTRAINT `Comment.AuthorId` FOREIGN KEY (`AuthorId`) REFERENCES `Author` (`Id`),
  CONSTRAINT `Comment.PostId` FOREIGN KEY (`PostId`) REFERENCES `Post` (`Id`)
);
  