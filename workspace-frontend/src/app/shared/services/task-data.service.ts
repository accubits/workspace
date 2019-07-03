import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class TaskDataService {
    //changeTaskDetails: Subject<any> = new Subject<any>(); // Keeping it for future ref
    constructor(
        private cookieService: CookieService
    ) {
        //   this.changeTaskDetails.subscribe((updatedTasks) => {
        //     this.getTasks.taskList = updatedTasks
        // });
    }

    // updatetasks():any{
    //   this.changeTaskDetails.next(this.getTasks.taskList);
    // }

   
    /* Init Data Models for task module[Start] */
    taskModels:any = {
        createTask: {
            title: '',
            description: '',
            priority: false,
            favourite: false,
            selectedPriority: '',
            startDate: null,
            endDate: null,
            reminder: null,
            responsiblePerson: {
                responsiblePersonName: this.cookieService.get('userName'),
                responsiblePersonId: this.cookieService.get('userSlug')
            },
            checklists: [],
            parentTask: {
                parentTaskSlug: "",
                parentTaskTitle: ""
            },
            approver: {
                approverName: this.cookieService.get('userName'),
                approverSlug: this.cookieService.get('userSlug')
            },
            participantIds: [],
            assignees: [

            ],

            fileList: [],
            existingFiles:[],
            responsiblePersonCanChangeTime: false,
            approveTaskCompleted: false,
            repeat: {
                repeatType: 'week',
                repeatEvery: 1,
                week: {
                    Sunday: false,
                    Monday: false,
                    Tuesday: false,
                    Wednesday: false,
                    Thursday: false,
                    Friday: false,
                    Saturday: false
                },
                ends: {
                    never: false,
                    on: null,
                    after: ""
                }
            },
            isTemplate: false,
            taskEndOption: 'never',
            to_all_participants: false,
        },
        getTasks: {
            selectedTab: 'overview',
            selectedSortItem: 'title',
            sortOrder: '',
            page: 1,
            perPage: 10,
            total:null,
            taskList: [],
            overviewTaskList:[],
            taskOverViewData: {},
            searchText: '',
            isFilterdBy: false,
            updateDueDate: {
                selectedTaskSlug: '',
                updatedDate: null
            },
            subtasksList:[]
        },
        taskRunManagement: {
            selectedAll: false,
            selectedTaskIds: [],
            selectedTaskIndex:1,
            status: '',
            showPopup: false,
            accepted: false,
            complete: false,
            pause: false,
            returnTask: false,
            start: false
        },

        taskBulkPriorityOrFav: {
            "taskSlugs": [
                
            ],
            "key": "",
            "value": true
        },
        detailPopup: {
            show: false
        },
        deletePopup: {
            show: false
        },
        archivePopup: {
            show: false
        },
        priorityorfav: {
            show: false
        },
        deleteBulkPopup: {
            show: false
        },
        completePopup:{
            show: false
        },
        showCreatetaskpopup: {
            show: false
        },
        responsiblePersons: {
            list: [],
            searchText: ''
        },
        taskStatus: {
            list: [],
            searchText: ''
        },
        filterList: {
            list: []
        },
        parentTasks: {
            list: [],
            searchText: ''
        },
        taskFilter: {
            taskFilterpopup: {
                show: false
            },
            editTaskFilter: {
                selectedTaskFilterSlug: ''
            },
            deleteTaskFilter:{
                deleteTaskFilterSlug:''
            },

            selectedFilters:{
                itemsInFilter:[]
            },

            createTaskFilter: {
                orgSlug: this.cookieService.get('orgSlug'),
                filterName: '',
                priority: null,
                favourite: null,
                withAttachement: null,
                includesSubtask: null,
                includesChecklist: null,
                dueDate: null,
                startDate: null,
                finishedOn: null,
                participant: [],
                taskStatus: [],
                responsiblePerson: <Array<any>> new Array(),
                createdBy: []
            },
        },
        taskDetails: {
            selectedTask: '',
            selected1: {},
            comments: [],
            subtasks: [],
            addedCommet: '',
            showEditTaskPop: false,
            replySlug: '',
            selectedcomment:'',
            likeStatus:false
        },
        updateTask:{
            tasks:[],
            action:'',
            status:''
        },
        updateCheckList:{
            checklistSlug:'',
            checklistStatus:false,
         
        },
        taskPartialUpdates: {
            "task_slug": "",
            "favourite": false,
            "priority": false,
            "end_date": "",
            "start_date": "",
            "reminder": "",
            "oldStart_date": "",
        },
    
        editTaksPopManagement:{
            showAttachments:false,
            showCheckList:false,
        },

        

        createTaksPopManagement:{
            showAttachments:false,
            showCheckList:false,
        },
        editTaskTemplate: {
            slug: '',
            title: '',
            description: '',
            startDate: null,
            endDate: null,
            responsiblePerson: {
                responsiblePersonName: '',
                responsiblePersonId: ''
            },
            creator: {
                creatorName: '',
                creatorSlug: ''
            },
            approver: {
                approverName: '',
                approverSlug: ''
            },
            responsiblePersonCanChangeTime: false,
            approveTaskCompleted: false,
            priority: false,
            favourite: false,
            assignees: [
            ],
            checklists: [],
            parentTask: {
                parentTaskSlug: "",
                parentTaskTitle: ""
            },
            existingFiles: [],
            reminder: null,
            repeat: {
                repeatType: 'week',
                repeatEvery: 1,
                week: {
                    Sunday: false,
                    Monday: false,
                    Tuesday: false,
                    Wednesday: false,
                    Thursday: false,
                    Friday: false,
                    Saturday: false
                },
                ends: {
                    never: false,
                    on: "",
                    after: ""
                }
            },
            participantIds:[],

            newFileList: [],
            taskEndOption: '',
            isTemplate: false
        },
        taskTemplates: {
            lists: [],
            selectedTemplateSlug: ''
        },
        to_all_participants: false,

        editTask:{
            showRepeat:false
        },

        createTaskRepeat:{
            showRepeat:false
        }
    }
    /* Init Data Models for task module[End] */

    responsiblePersons = { ...this.taskModels.responsiblePersons };
    taskStatus = { ... this.taskModels.taskStatus };
    filterList = { ... this.taskModels.filterList };
    parentTasks = { ...this.taskModels.parentTasks };
    createTask = { ...this.taskModels.createTask };
    createTaskRepeat = { ...this.taskModels.createTaskRepeat };
    editTask = { ...this.taskModels.editTask };
    getTasks = { ...this.taskModels.getTasks };
    detailPopup = { ...this.taskModels.detailPopup };
    deletePopup = { ...this.taskModels.deletePopup };
    priorityorfav = { ...this.taskModels.priorityorfav };
    archivePopup = { ...this.taskModels.archivePopup };
    taskBulkPriorityOrFav = { ...this.taskModels.taskBulkPriorityOrFav };
    deleteBulkPopup = { ...this.taskModels.deleteBulkPopup };
    completePopup = { ...this.taskModels.completePopup};
    showCreatetaskpopup = { ...this.taskModels.showCreatetaskpopup };
    updateTask = {...this.taskModels.updateTask};
    updateCheckList = {...this.taskModels.updateCheckList};

    taskRunManagement = { ...this.taskModels.taskRunManagement };
    taskDetails = { ...this.taskModels.taskDetails };
    taskPartialUpdates = { ...this.taskModels.taskPartialUpdates };
    editTaskTemplate = { ...this.taskModels.editTaskTemplate };
    editTaksPopManagement = { ...this.taskModels.editTaksPopManagement };
    createTaksPopManagement = { ...this.taskModels.createTaksPopManagement };
    taskTemplates = { ...this.taskModels.taskTemplates };

    apicall = {
        inprogress: false
    }


    /* #Task Filter Area */
    taskFilterpopup = { ...this.taskModels.taskFilter.taskFilterpopup };
    createTaskFilter =  Object.assign({}, this.taskModels.taskFilter.createTaskFilter); //{ ...this.taskModels.taskFilter.createTaskFilter };//JSON.parse(JSON.stringify(this.taskModels.taskFilter.createTaskFilter));//
    editTaskFilter = { ...this.taskModels.taskFilter.editTaskFilter }
    deleteTaskFilter = { ...this.taskModels.taskFilter.deleteTaskFilter};
    selectedFilters= {...this.taskModels.taskFilter.selectedFilters}

    resetCreateTask(): void {
        this.taskModels.createTask.participantIds =  [];
        this.taskModels.createTask.assignees =  [];
        this.taskModels.createTask.fileList =  [];
        this.taskModels.createTask.existingFiles =  [];
        this.taskModels.createTask.checklists =  [];
        this.taskModels.createTask.repeat =  {
                repeatType: 'week',
                repeatEvery: 1,
                week: {
                    Sunday: false,
                    Monday: false,
                    Tuesday: false,
                    Wednesday: false,
                    Thursday: false,
                    Friday: false,
                    Saturday: false
                },
                ends: {
                    never: false,
                    on: null,
                    after: ""
                }
            }
        
 

        this.createTask = { ...this.taskModels.createTask };
        this.showCreatetaskpopup = { ...this.taskModels.showCreatetaskpopup };
        this. createTaksPopManagement = { ...this.taskModels.createTaksPopManagement };
    }

    resetCreateTaskTepmlate(): void {
        this.taskModels.createTask.participantIds =  [];
        this.taskModels.createTask.assignees =  [];
        this.taskModels.createTask.fileList =  [];
        this.taskModels.createTask.existingFiles =  [];
        this.taskModels.createTask.checklists =  [];
        this.taskModels.createTask.repeat =  {
                repeatType: 'week',
                repeatEvery: 1,
                week: {
                    Sunday: false,
                    Monday: false,
                    Tuesday: false,
                    Wednesday: false,
                    Thursday: false,
                    Friday: false,
                    Saturday: false
                },
                ends: {
                    never: false,
                    on: null,
                    after: ""
                }
            }
        
 

        this.createTask = { ...this.taskModels.createTask };
        this. createTaksPopManagement = { ...this.taskModels.createTaksPopManagement };
    }

    resetFilter(): void {
        this.taskModels.taskFilter.createTaskFilter.participant = [];
        this.taskModels.taskFilter.createTaskFilter.taskStatus = [];
        this.taskModels.taskFilter.createTaskFilter.createdBy = [];
        this.taskModels.taskFilter.createTaskFilter.responsiblePerson = new Array();
        this.getTasks.isFilterdBy = false;
        this.createTaskFilter = { ...this.taskModels.taskFilter.createTaskFilter };
    }

    resetTaskRunManagement(): void {
        this.taskModels.taskRunManagement.selectedTaskIds = [];
        this.taskRunManagement = { ...this.taskModels.taskRunManagement };
    }
    resetPartialUpdates(): void {
        this.taskPartialUpdates = { ...this.taskModels.taskPartialUpdates };
    }

    resetUpdateTask():void{
        this.taskModels.updateTask.tasks = [],
        this.updateTask = {...this.taskModels.updateTask};
   
    }
    resetGetTask():void{
        this.taskModels.getTasks.overviewTaskList = [];
        this.taskModels.getTasks.taskList = [];
        this.getTasks = {...this.taskModels.getTasks};
    }

    resetEdit():void{
        this.taskModels.editTaskTemplate.assignees = [];
        this.taskModels.editTaskTemplate.newFileList= [];
        this.taskModels.editTaskTemplate.checklists= [];
        this.taskModels.editTaskTemplate.participantIds =  [];
        this.editTaskTemplate = { ...this.taskModels.editTaskTemplate };
        this.editTaksPopManagement = { ...this.taskModels.editTaksPopManagement };
    }
    resetSelectedTaskDetails():void{
        this.taskModels.taskDetails.comments = [];
        this.taskModels.taskDetails.subtasks = [];
        this.taskDetails = { ...this.taskModels.taskDetails };
    }
}
