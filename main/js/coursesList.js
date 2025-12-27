const searchWrapper = document.querySelector(".search-input");
const inputBox = searchWrapper.querySelector("input");
const suggBox = searchWrapper.querySelector(".autocom-box");
const icon = searchWrapper.querySelector(".icon");
let linkTag = searchWrapper.querySelector("a");
let webLink;
let alreadyRegistered = true ;

if (convertedSchedule == "")
{
	convertedSchedule = "[]"
	alreadyRegistered = false;
}

convertedSchedule = JSON.parse(convertedSchedule)

inputBox.onkeyup = (e)=>{
    let userData = e.target.value; //user enetered data
    let emptyArray = [];
    if(userData){

		
		for (const [key, value] of Object.entries(coursesData)) {
			if ( (coursesData[key]["CourseName"].toLocaleLowerCase()).includes(userData.toLocaleLowerCase()))
			{
				emptyArray.push(coursesData[key]["CourseName"])
			}
		}
		
        emptyArray = emptyArray.map((data)=>{
            // passing return data inside li tag
            return `<li>${data}</li>`;
        });
        searchWrapper.classList.add("active"); 
        showSuggestions(emptyArray);
        let allList = suggBox.querySelectorAll("li");
        for (let i = 0; i < allList.length; i++) {
            allList[i].setAttribute("onclick", "select(this)");
        }
    }else{
        searchWrapper.classList.remove("active"); 
		showSuggestions(emptyArray);
    }
}
function select(element){
    let selectData = element.textContent;
    inputBox.value = selectData;
    searchWrapper.classList.remove("active");
	showSuggestions([]);
}
function showSuggestions(list){
    let listData;
    if(!list.length){
        userValue = inputBox.value;
        listData = `<li>${userValue}</li>`;
    }else{
      listData = list.join('');
    }
    suggBox.innerHTML = listData;
}







const addBtn = document.getElementById('addCourseButton');
const todoList = document.querySelector(".todoList");

let listArray = []

function showTasks(){
	let newLiTag = "";
	listArray.forEach((element, index) => {
		newLiTag += `<li>${element}<span class="icon" onclick="deleteTask(${index})"><i class="fas fa-trash"></i></span></li>`;
	});
	todoList.innerHTML = newLiTag; 
	inputBox.value = ""; 
}
  
showTasks(); 

addBtn.onclick = ()=>{ 
	let userEnteredValue = inputBox.value; 
	let flag = false;
	for (const [key, value] of Object.entries(coursesData)) {
		if (coursesData[key]["CourseName"] == userEnteredValue)
		{
			flag = true;
		}
	}

	if (flag)
	{
		if (! listArray.includes(userEnteredValue))
		{
			listArray.push(userEnteredValue); 
			showTasks(); 
			addBtn.classList.remove("active"); 
		}
	}
}


function deleteTask(index){
  listArray.splice(index, 1); 
  showTasks();
}

// Algoirthm

class Date
{
	constructor(day, startingHour, endingHour)
	{
		this.day = day
        this.startingHour = startingHour
        this.endingHour = endingHour
	}   
}
    

class Course
{
	constructor(courseCode, instructor, date , seats)
	{
		this.courseCode = courseCode
        this.instructor = instructor
        this.date = date
		this.seats = seats
	}
}
    

function checkValidity(schedule, desiredCourses, coursesCombination)
{
	let weekDays = [[] , [] , [] , [] , [] , [] , [] ] 
	
	for (let i = 0; i < desiredCourses.length; i++) {
        course = schedule[desiredCourses[i]][coursesCombination[i]];
        weekDays[course.date.day].push([course.date.startingHour, course.date.endingHour,course.seats]);
	}
	
	for (let o = 0 ; o < weekDays.length ; o++)
	{
		let day = weekDays[o];
		if (day.length <= 1)
		{
			continue;
		}
		// Check seats
		for (let i = 0; i < day.length; i++) {
			if (day[i][2] <= 0)
			{
				return false;
			}
		}
		for (let i = 0; i < day.length - 1; i++) {
			for (let j = i+1; j < day.length; j++) {
				if ((day[i][0] < day[j][0] && day[i][1] > day[j][0]) || (day[i][0] > day[j][0] && day[j][1] < day[i][0]) || (day[i][0] == day[j][0]))
				{
					return false;
				}
			}
		} 
	}

    return true;
}
    
function sum (list)
{
	let x = 0;
	for (let i = 0 ; i < list.length ;i++)
	{
		x += list[i]
	}
	return x;
}	

function filterScheduleToSameSectionNumber (result)
{
	let newList = [] ;
	for (let i = 0; i < result.length ; i++)
	{
		let flag = false;
		for (let j = 0 ; j < result[i].length -1 ; j+= 2)
		{
			if (result[i][j] != result[i][j+1])
			{
				flag = true;
				break;
			}
		}
		if (!flag)
		{
			newList.push(result[i].slice());
		}
	}
	return newList
}
	
function getAllSchedules(schedule, desiredCourses)
{
    let maxIterations = 0;
    for (let i = 0; i < desiredCourses.length ; i++) {
        maxIterations += schedule[desiredCourses[i]].length-1 ;
    }
    let result = [];
    let coursesCombination = [];
    for (let i = 0 ; i < desiredCourses.length ; i++)
    {
        coursesCombination.push(0);
    }
    while (maxIterations > sum(coursesCombination))
    {
        if (checkValidity(schedule,desiredCourses, coursesCombination))
        {
            result.push(coursesCombination.slice());
        }
        for (let i = 0 ; i < coursesCombination.length ; i++)
        {
            if (coursesCombination[i] + 1 < schedule[desiredCourses[i]].length)
            {
                coursesCombination[i] += 1;
                break;
            }
            else
            {
                coursesCombination[i] = 0;
            }
        }
    }
	//console.log(filterScheduleToSameSectionNumber(result))
    return result;
}

// Table


let myTable = document.querySelector('#table');
let timeTable = [
    { time: '9:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
    { time: '10:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '11:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '12:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '13:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '14:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '15:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '16:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' },
	{ time: '17:00', sunday: '', Monday: '' , Tuesday : '' , Wednesday : '' , Thursday : '' }
]
let headers = ['Time/Day','Sunday', 'Monday', 'Tuesday','Wednesday','Thursday'];

let htmlData = []
let CreditTextSchedule = null

let table = document.createElement('table');
	table.className = 'content-table';
	table.style.width = '100%';

	let tableColumnWidth = document.createElement('colgroup')
	for (let i = 0 ; i < 6 ; i++)
	{
		let col1 = document.createElement('col')
		col1.style.width = '20%'
		tableColumnWidth.appendChild(col1)
	}
	let threadGamed = document.createElement('thead');
    let headerRow = document.createElement('tr');
    headers.forEach(headerText => {
        let header = document.createElement('th');
        let textNode = document.createTextNode(headerText);
        header.appendChild(textNode);
        headerRow.appendChild(header);
    });
	threadGamed.appendChild(headerRow);
    table.appendChild(threadGamed);
	let i = 0;
    timeTable.forEach(emp => {
        let row = document.createElement('tr');
		row.className = 'active-row';
		htmlData.push([])
		let anotherThread = document.createElement('tbody');
        Object.values(emp).forEach(text => {
            let cell = document.createElement('td');
			htmlData[i].push(cell);
            let textNode = document.createTextNode(text);
            cell.appendChild(textNode);
            row.appendChild(cell);
        })
		i++
		anotherThread.appendChild(row);

		table.appendChild(anotherThread);
		

		

        
    });
	// Credits bar
	let creditBar = document.createElement('thead');

	let creditHeader = document.createElement('tr');

	let creditText1 = document.createElement('th');
	let creditText1Node = document.createTextNode("")
	creditText1.appendChild(creditText1Node);
	CreditTextSchedule = creditText1

	let creditText2 = document.createElement('th');
	let creditText3 = document.createElement('th');
	let creditText4 = document.createElement('th');
	let creditText5 = document.createElement('th');
	let creditText6 = document.createElement('th');
	
	creditBar.appendChild(creditHeader)
	
	creditHeader.appendChild(creditText1)
	creditHeader.appendChild(creditText2)
	creditHeader.appendChild(creditText3)
	creditHeader.appendChild(creditText4)
	creditHeader.appendChild(creditText5)
	creditHeader.appendChild(creditText6)

	//

	table.appendChild(creditBar)
    myTable.appendChild(table);


TimeTableDict = {
	9 : 0,
	10 : 1,
	11 : 2,
	12 : 3,
	13 : 4,
	14 : 5,
	15 : 6,
	16 : 7,
	17 : 8,
	18 : 9
}

function convertScheduleData(List)
{
	for (let i = 0 ; i < List.length ;i++)
	{
		List[i] = [List[i].courseCode , List[i].date.day , List[i].date.startingHour , List[i].date.endingHour ]
	}
	convertedSchedule = List.slice
	return List
}

function resetSchedule ()
{
	for (let i = 0 ; i < htmlData.length ;i++)
	{
		for (let j = 1 ; j < htmlData[0].length ;j++)
		{
			htmlData[i][j].innerHTML = "";
		}
	}
	CreditTextSchedule.innerHTML = "";
}
function showSchedule (List,onlyNames,creditHours)
{
	resetSchedule()

	if (onlyNames)
	{
		newList = []
		creditHours = 0;
		for (let i = 0 ; i < List.length ; i++)
		{
			data = List[i].split("-");

			type = data[1] == "Lec" ? "Lecture" : (data[1] == "TUT" ? "Tutorial" : data[1])
			n = parseInt(data[2])-1

			collectedData = coursesData[data[0]]['CourseData'][type][n]
			creditHours += coursesData[data[0]]['Credits'];

			newList.push ([List[i] , collectedData['Day'], collectedData['Start'] , collectedData['End'] ] )
		}

		List = newList
	}

	convertedSchedule = []
	for (let i = 0 ; i < List.length ; i++)
	{
		if (!onlyNames)
		{
			convertedSchedule.push(List[i][0])
		}

		x = List[i][1]
		y = TimeTableDict[List[i][2]]
		htmlData[y][x].innerHTML = List[i][0];
		if (List[i][3] - List[i][2] == 2)
		{
			htmlData[y+1][x].innerHTML = List[i][0];
		}
	}

	CreditTextSchedule.innerHTML = "CH: " +creditHours;
}	

showSchedule(convertedSchedule,true,0)

// Generate Schedules 

function getCoursesCreditHours (listArray)
{
	Credits = 0;
	for (let i = 0 ; i < listArray.length ;i++) 
	{
		let courseCode = getCourseCodeFromName(listArray[i]); 
		Credits += coursesData[courseCode]['Credits'];
	}
	return Credits;
}

GradeQuailty = {
	"A+" : 4.0,
	"A" : 4.0,
	"A-" : 3.7,
	"B+" : 3.3,
	"B" : 3.0,
	"B-" : 2.7,
	"C+" : 2.3,
	"C" : 2.0,
	"C-" : 1.7,
	"D+" : 1.3,
	"D" : 1.0,
	"F" : 0
};

function getUserGPA ()
{
	if (userGrades == "")
	{
		return 4;
	}

	CreditsCurrentTable = 0
	QualityPointCurrentTable = 0
	Grades = JSON.parse(userGrades)
	for (const [key, value] of Object.entries(Grades)) {
		for (const [key1, value1] of Object.entries(value)) {
			CreditsCurrentTable += parseInt(value1[2]);
        	QualityPointCurrentTable += parseInt(value1[2]*GradeQuailty[value1[0]]);
		}
	}
	return QualityPointCurrentTable/CreditsCurrentTable;
}

function checkIfUserTookThatCourseBefore (courseCode)
{
	if (userGrades == "")
	{
		return 0;
	}
	Grades = JSON.parse(userGrades)
	for (const [key, value] of Object.entries(Grades)) {
		if (value[courseCode])
		{
			return value[courseCode][0] == 'F'? 0 : 1;
		}
	}
	return 0;
}



function getCourseCodeFromName (CourseName)
{
	for (const [key, value] of Object.entries(coursesData)) {
		if (coursesData[key]["CourseName"] == CourseName)
		{
			return key;
		}
	}
}
let schedulesData = []
let scheduleIndex = 1;
document.getElementById('GenerateSchedules').onclick = ()=>{ 	

	// check user GPA first

	if (getCoursesCreditHours(listArray) > 12 && getUserGPA() < 2 )
	{
		Swal.fire(
			'Generate Schedule',
			'In order to take over 12 credit hour courses you should be over 2.0 GPA\n and the courses you would like to have ' +getCoursesCreditHours(listArray) +' credit hour' ,
			'error'
		)
		return ;
	}

	if (getCoursesCreditHours(listArray) > 18 && getUserGPA() < 3 )
	{
		Swal.fire(
			'Generate Schedule',
			'In order to take over 18 credit hour courses you should be over 3.0 GPA\n and the courses you would like to have ' +getCoursesCreditHours(listArray) +' credit hour' ,
			'error'
		)
		return ;
	}

	if (getCoursesCreditHours(listArray) > 21 )
	{
		Swal.fire(
			'Generate Schedule',
			'You can\'t register more than 21 credit hour',
			'error'
		)
		return ;
	}

	// check prequisties first
	for (let i = 0 ; i < listArray.length ;i++) 
	{
		let courseCode = getCourseCodeFromName(listArray[i]); 
		for (let j = 0 ; j < coursesData[courseCode]['prerequisites'].length ; j++)
		{
			if (!checkIfUserTookThatCourseBefore(coursesData[courseCode]['prerequisites'][j]))
			{
				Swal.fire(
					'Generate Schedule',
					'In order to take ' + listArray[i] + " you should first take " + coursesData[courseCode]['prerequisites'][j],
					'error'
				)
				return ;
			}
		}
	}
	
	let schedule = {} // "CSCI201Lecture" : [Course(courseCode, instructor, date(day, startingHour, endingHour)), Course(//), Course(//)]
	let desiredCourses = []
	schedulesData = []

	for (let i = 0 ; i < listArray.length ;i++) 
	{
		let courseCode = getCourseCodeFromName(listArray[i]); 
		let data = coursesData[courseCode]

		
		
		schedule[courseCode+"Lecture"] = []
		for (let j = 0 ; j < data["CourseData"]["Lecture"].length ; j++)
		{
			let data2 = data["CourseData"]["Lecture"][j];
			schedule[courseCode+"Lecture"].push(new Course (courseCode + "-Lec-" + String(j+1),data2["Insturctor"] ,new Date(data2["Day"],data2["Start"],data2["End"]) , data2['Seats'] ))
		}
		desiredCourses.push(courseCode+"Lecture");
		
		if (data["Tutorial"])
		{
			schedule[courseCode+"Tutorial"] = []
			for (let j = 0 ; j < data["CourseData"]["Tutorial"].length ; j++)
			{
				let data2 = data["CourseData"]["Tutorial"][j];
				schedule[courseCode+"Tutorial"].push(new Course (courseCode + "-TUT-" + String(j+1),data2["Insturctor"] ,new Date(data2["Day"],data2["Start"],data2["End"]) , data2['Seats'] ))
			}
			desiredCourses.push(courseCode+"Tutorial");
		}
		
		if (data["Lab"])
		{
			schedule[courseCode+"Lab"] = []
			for (let j = 0 ; j < data["CourseData"]["Lab"].length ; j++)
			{
				let data2 = data["CourseData"]["Lab"][j];
				schedule[courseCode+"Lab"].push(new Course (courseCode + "-Lab-" + String(j+1) ,data2["Insturctor"] ,new Date(data2["Day"],data2["Start"],data2["End"]) , data2['Seats'] ))
			}
			desiredCourses.push(courseCode+"Lab");
		}
	
		
	}
	
	let list = getAllSchedules(schedule,desiredCourses);
	
	for (let i = 0 ; i < list.length ; i++)
	{
		schedulesData[i] = []
		for (let j = 0 ; j < list[i].length ; j++)
		{
			schedulesData[i].push(schedule[desiredCourses[j]][list[i][j]])
		}
	}
	
	scheduleIndex = 1;
	if (schedulesData.length > 0)
	{
		showSchedule(convertScheduleData(schedulesData[scheduleIndex-1].slice()),false,getCoursesCreditHours(listArray))
		
		document.getElementById('SchedulesIndex').innerHTML = String(scheduleIndex) + "/" + schedulesData.length  ; 
	}
	else {
		resetSchedule();
		document.getElementById('SchedulesIndex').innerHTML = "None"  ; 
	}
	
	
	
}

document.getElementById('NextTable').onclick = ()=>{ 
	if (scheduleIndex + 1 <= schedulesData.length)
	{
		scheduleIndex++;
		showSchedule(convertScheduleData(schedulesData[scheduleIndex-1].slice()),false,getCoursesCreditHours(listArray))
		document.getElementById('SchedulesIndex').innerHTML = String(scheduleIndex) + "/" + schedulesData.length  ; 
	}
}

document.getElementById('PrevTable').onclick = ()=>{ 
	if (scheduleIndex - 1 > 0)
	{
		scheduleIndex--;
		showSchedule(convertScheduleData(schedulesData[scheduleIndex-1].slice()),false,getCoursesCreditHours(listArray))
		document.getElementById('SchedulesIndex').innerHTML = String(scheduleIndex) + "/" + schedulesData.length  ; 
	}
}



document.getElementById('registerSchedule').onclick = (e) =>
{
	e.preventDefault()

	if (!alreadyRegistered)
	{
		var request = new XMLHttpRequest()
		request.open("POST", "../database.php", true)
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.onload = function ()
		{
			console.log(this.responseText)
		}
		request.send("registerSchedule="+JSON.stringify(convertedSchedule))

		alreadyRegistered = true;
		Swal.fire(
			'Register Schedule',
			'You have succesfully registered the schedule.',
			'success'
		)
	} else {
		Swal.fire(
			'Register Schedule',
			'Drop the registered schedule first.',
			'error'
		)
	}
	
}	

document.getElementById('dropSchedule').onclick = (e) =>
{
	e.preventDefault()

	if (alreadyRegistered)
	{
		resetSchedule()

		var request = new XMLHttpRequest()
		request.open("POST", "../database.php", true)
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.onload = function ()
		{
			console.log(this.responseText)
		}
		request.send("dropSchedule=")

		alreadyRegistered = false;
		Swal.fire(
			'Drop Schedule',
			'You have succesfully droped the schedule.',
			'success'
		)
	} else {
		Swal.fire(
			'Drop Schedule',
			'Register a schedule first',
			'error'
		)
	}
	
}	



