import random


def generateDeleteAndCreateTableQueries(outputFile, tableName):
    ''' A utility function for generate SQL queries that create a table with the
    specified name, dropping the table if it already exists.

    @param outputFile: The file to write the queries to.
    @param tableName: The name of the table to create.
    '''
    sqlStatement = "DROP TABLE IF EXISTS {};".format(tableName)
    outputFile.write(sqlStatement)
    outputFile.write("\n")
    sqlStatement = "CREATE TABLE IF NOT EXISTS {} (".format(tableName) \
        + "otherCourseCode varchar(20), " \
        + "otherSchool varchar(100), " \
        + "localCourseCode varchar(20), " \
        + "isApproved tinyint(1), " \
        + "approvedBy varchar(100), " \
        + "PRIMARY KEY (otherCourseCode, otherSchool, localCourseCode, " \
        + "isApproved, approvedBy)" \
        + ");"
    outputFile.write(sqlStatement)
    outputFile.write("\n")


def generateQueriesToFile(outputFile, numQueries):
    ''' A utility function for generating a bunch of SQL INSERT statements, that
    may be used to populate a MySQL table with a bunch of dummy information.

    @param outputFile: The file to write the queries to.
    @param numQueries: The number of queries to generate.
    '''
    # Delete and create the table, to make sure only the given test data is
    # entered into the table.
    tableName = "coen174lProject"
    generateDeleteAndCreateTableQueries(outputFile, tableName)
    courseCodeNumbers = (101, 102, 201, 50, 51, 52, 11, 12, 13, 14, '11A',
                         '11B', '11C')
    courseCodePrefices = ("COEN", "CSCI", "CS", "ELEN", "ENGR", "MATH")
    otherSchoolNames = ("Stanford University", "UC Berkeley",
                        "UC San Diego", "UC Santa Barbara",
                        "University of Waterloo", "Harvard University")
    professors = ("Nam Ling", "Maya Ackerman", "Ahmed Amer",
                  "Moe Amouzgar", "Darren Atkinson", "Ronald Danielson",
                  "Ruth Davis", "Behnam Dezfouli", "Farokh Eskafi",
                  "Yi Fang", "Silvia Figueira", "JoAnne Holliday",
                  "Ha Yang Kim", "Daniel Lewis", "Yuhong Liu",
                  "Rani Mikkilineni", "Keyvan Moataghed",
                  "Angela Musurlian")

    for i in range(numQueries):
        # Determine the terms that will be used in this SQL statement.
        otherCourseCodeNumber = random.choice(courseCodeNumbers)
        otherCourseCodePrefix = random.choice(courseCodePrefices)
        otherCourseCode = "{} {}".format(otherCourseCodePrefix,
                                         otherCourseCodeNumber)
        localCourseCodeNumber = random.choice(courseCodeNumbers)
        localCourseCodePrefix = random.choice(courseCodePrefices)
        localCourseCode = "{} {}".format(localCourseCodePrefix,
                                         localCourseCodeNumber)
        otherSchoolName = random.choice(otherSchoolNames)
        isApproved = random.randint(0, 1)
        professor = random.choice(professors)

        # Construct the SQL statement.
        sqlStatement = "INSERT INTO coen174lProject (otherCourseCode, " \
            + "otherSchool, localCourseCode, isApproved, approvedBy) " \
            + "VALUES('{}', '{}', '{}', {}, '{}');".format(otherCourseCode,
                                                           otherSchoolName,
                                                           localCourseCode,
                                                           isApproved,
                                                           professor)

        # Write the SQL statement to the file.
        outputFile.write(sqlStatement)
        outputFile.write("\n")


if __name__ == "__main__":
    numQueries = 100
    with open("TestQueries.sql", 'w') as outputFile:
        generateQueriesToFile(outputFile, numQueries)
