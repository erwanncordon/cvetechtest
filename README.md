# RESTful Technical Test

## Prerequisites

You are expected to have access to the Internet during the course of the development of this task, it is expected that you will have a suitable professional environment in which to complete the test.

If you feel you do not meet these requirements, please contact the person who directed you to this test, and they can make arrangements for such things to be arranged.

## Instructions

The purpose of this test is to produce a small working application for review by your prospective employer, the details of the application have been provided below.

The aim for the test is to understand how you structure your solution, and how you turn that solution into a working application. 

You need to:

* Produce working, object orientated, unit-tested code
* Describe the setup of your project, and how to resolve any external dependencies 
* Deliver the working application in some form to your prospective employer

You are expected to:

* Work on this task alone without help from anyone else
* Ask questions if you do not understand anything you are being asked to do

This test has no time limit given, what we expect you to do is read this brief, and reply to the person who asked you to complete it with your estimate on how long it will take you to complete the task.

### The Assignment

Before you begin to write your solution to the assignment, you should first fork this repository. You should (as you would with any project) commit regularly to your forked repository, and give the link to the person asking you to complete this test.

`CVE is a dictionary of publicly known information security vulnerabilities and exposures.`

Vulnerabilities that are found on publicly accessible software are generally assigned a CVE (Common Vulnerabilities and Exposures) reference number, as a singular way to reference a specific vulnerability without a more complex naming system having to remembered for each vulnerability.

These CVE's contain information about the vulnerability itself, what software it affects, and what versions of that software, as well as some other useful information to help those attempting to discover if they are vulnerable, or attempting to remove that vulnerability from their software. This information is collated and presented by a not for profit called Mitre, is partially funded by the US Government. 

On the Mitre website, they allow you to download all of the vulnerabilities found since the project started in 1999, they allow you to download all of the CVE's in various formats, including XML, TXT and CSV. 

Your task is to create a RESTful API that presents this data in a structured and resource orientated manner. Your API should show the information for that CVE found in the file you downloaded from the Mitre website. You should show all of the details you can about the CVE being requested by the user.

Your completed application should present the following interfaces and functionality:

| Method / Interface  | Description |
| ------------------- | ------------- |
| GET - /cve/:cveNumber  | Return a single CVE resource  |
| GET - /cve  | Return multiple CVE resources. It should support: limiting of results, result offsets, the year of the vulnerabilities publishing  |

Your interfaces should respond contextually based upon the Accept header sent by the requesting client, you may default to what ever preset you prefer.

We would like you to send responses in application/xml and application/json, however you may add other supported response types if you would prefer.

You should unit test your application, and if you feel so inclined we welcome you to write integration and full stack tests, depending on your experience with such technologies.

## Things to remember

When writing a RESTful API you are representing a *resource* above all other things, and should be mindful in your application's public interfaces because of this

You are welcome to store the CVE's you download from Mitre in what ever fashion you wish, we suggest that you use a database to store the values from the file you download from Mitre, however you could also pre-parse the file and store the pointers to each CVE within the file.


