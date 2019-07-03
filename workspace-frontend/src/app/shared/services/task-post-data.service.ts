import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { TaskDataService } from './task-data.service'
import { ActStreamDataService } from './act-stream-data.service'
import { UtilityService } from './utility.service'


@Injectable()
export class TaskPostDataService {

  constructor(
    public taskDataService: TaskDataService,
    private utilityService: UtilityService,
    private actStreamDataService:ActStreamDataService,
    private cookieService: CookieService,
  ) { }


  /* Prepare Create Task Post Variable[start] */
  prepareCreateTaskPost(): any {
    let postData = {
      "title": this.taskDataService.createTask.title,
      "description": this.taskDataService.createTask.description,
      "participants": this.taskDataService.createTask.participantIds,
      "org_slug": this.cookieService.get('orgSlug'),
      "responsible_person": this.taskDataService.createTask.responsiblePerson.responsiblePersonId,
      "approver":this.taskDataService.createTask.approver.approverSlug,
      "checklist": this.taskDataService.createTask.checklists,
      "repeatable":null,

      "reminder": this.utilityService.convertToUnix(this.taskDataService.createTask.reminder),
      "parent_task": this.taskDataService.createTask.parentTask.parentTaskSlug,
      "start_date": this.utilityService.convertToUnix(this.taskDataService.createTask.startDate),
      "end_date": this.utilityService.convertToUnix(this.taskDataService.createTask.endDate),
      "responsible_person_change_time": this.taskDataService.createTask.responsiblePersonCanChangeTime,
      "approve_task_completed": this.taskDataService.createTask.approveTaskCompleted,
      "priority": this.taskDataService.createTask.priority,
      "favourite": this.taskDataService.createTask.favourite,
      "is_template": this.taskDataService.createTask.isTemplate,
      "to_all_participants" : this.taskDataService.createTask.to_all_participants,
      "existingFiles":this.taskDataService.createTask.existingFiles
    }

    if(!this.taskDataService.createTaskRepeat.showRepeat){
      postData.repeatable =  null;
    }else{
      postData.repeatable = {
        "repeat_type": this.taskDataService.createTask.repeat.repeatType.toLowerCase(),
        "repeat_every": this.taskDataService.createTask.repeat.repeatEvery,
        "week": this.taskDataService.createTask.repeat.repeatType !== 'week' ? null : this.taskDataService.createTask.repeat.week,
        "ends": {
          "never": this.taskDataService.createTask.taskEndOption === 'never' ? true : false,
          "on": this.taskDataService.createTask.taskEndOption === 'on' ? this.utilityService.convertToUnix(this.taskDataService.createTask.repeat.ends.on) : null,
          "after": this.taskDataService.createTask.taskEndOption === 'after' ? this.taskDataService.createTask.repeat.ends.after : null
        },
      }
    }

    // for(let i=0;i<this.taskDataService.createTask.assignees.length;i++){
    //   if(this.taskDataService.createTask.assignees[i].assigneeSlug)
    //     postData.participants.push(this.taskDataService.createTask.assignees[i].assigneeSlug)
    // }

    // console.log(JSON.stringify(postData));
    return JSON.stringify(postData);
  }
  /* Prepare Create Task Post Variable[End]} */

  /* Prepare Create Task Post Variable[start] */
  prepareManagePost(): any {
    let postData = {
      "tasks": this.taskDataService.taskRunManagement.selectedTaskIds,
      "action": this.taskDataService.taskRunManagement.selectedTaskIds.length === 1 ? 'single' : "multiple",
      "status": this.taskDataService.taskRunManagement.status
    }
    return postData;
  }
  /* Prepare Create Task Post Variable[End]} */

  /* Prepare Create Task Post Variable[start] */
  widgetStatusPost(): any {
    let postData = {
      "tasks": this.actStreamDataService.taskRunManagement.selectedTaskIds,
      "action": this.actStreamDataService.taskRunManagement.selectedTaskIds.length === 1 ? 'single' : "multiple",
      "status": this.actStreamDataService.taskRunManagement.status
    }
    return postData;
  }
  /* Prepare Create Task Post Variable[End]} */

  /*Edit Task Post[Start] */
  prepareEditTaskPost(): any {
    let postData = {
      "title": this.taskDataService.editTaskTemplate.title,
      "description": this.taskDataService.editTaskTemplate.description,
      "participants": this.taskDataService.editTaskTemplate.participantIds,
      "org_slug": this.cookieService.get('orgSlug'),
      "responsible_person": this.taskDataService.editTaskTemplate.responsiblePerson.responsiblePersonId,
      "approver":this.taskDataService.editTaskTemplate.approver.approverSlug,
      // "checklist":this.taskDataService.editTaskTemplate.checklists,
      "checklist":[],
      "existingFiles": this.taskDataService.editTaskTemplate.existingFiles,
      "repeatable": {
        "repeat_type": this.taskDataService.editTaskTemplate.repeat.repeatType,
        "repeat_every": this.taskDataService.editTaskTemplate.repeat.repeatEvery,
        "week": this.taskDataService.editTaskTemplate.repeat.repeatType !== 'week' ? null : this.taskDataService.editTaskTemplate.repeat.week,
        "ends": {
          "never": this.taskDataService.editTaskTemplate.taskEndOption=== 'never' ? true : false,
          "on": this.taskDataService.editTaskTemplate.taskEndOption === 'on' ? this.utilityService.convertToUnix(this.taskDataService.editTaskTemplate.repeat.ends.on) : null,
          "after": this.taskDataService.editTaskTemplate.taskEndOption === 'after' ? this.taskDataService.editTaskTemplate.repeat.ends.after : null
        },
      },
      "parent_task": this.taskDataService.editTaskTemplate.parentTask.parentTaskSlug,
      "reminder": this.utilityService.convertToUnix(this.taskDataService.editTaskTemplate.reminder),
      "start_date": this.utilityService.convertToUnix(this.taskDataService.editTaskTemplate.startDate),
      "end_date": this.utilityService.convertToUnix(this.taskDataService.editTaskTemplate.endDate),
      "responsible_person_change_time": this.taskDataService.editTaskTemplate.responsiblePersonCanChangeTime,
      "approve_task_completed": this.taskDataService.editTaskTemplate.approveTaskCompleted,
      "priority": this.taskDataService.editTaskTemplate.priority,
      "favourite": this.taskDataService.editTaskTemplate.favourite,
      "is_template": this.taskDataService.editTaskTemplate.isTemplate,
      "to_all_participants" : this.taskDataService.taskDetails.selectedTaskDetails.isAllParticipants 
    }

    // for(let i=0;i<this.taskDataService.editTaskTemplate.assignees.length;i++){
    //   if(this.taskDataService.editTaskTemplate.assignees[i].assigneeSlug)
    //     postData.participants.push(this.taskDataService.editTaskTemplate.assignees[i].assigneeSlug)
    // }

    for(let i=0;i<this.taskDataService.editTaskTemplate.checklists.length;i++){
           postData.checklist.push({
            "description":this.taskDataService.editTaskTemplate.checklists[i].description ,
            "checklist_id": this.taskDataService.editTaskTemplate.checklists[i].slug,
            "is_checked": this.taskDataService.editTaskTemplate.checklists[i].checklistStatus
           })
    }

    return JSON.stringify(postData);
    
  }


  /*Edit Task Post[End] */

  /* Prepare Create Task Filter Post Variable[start] */
    prepareCreateTaskFilterPost(): any {
   //  this.taskDataService.createTaskFilter.orgSlug = this.cookieService.get('orgSlug');
     this.taskDataService.createTaskFilter.startDate = this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.startDate);
     this.taskDataService.createTaskFilter.dueDate = this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.dueDate);
     this.taskDataService.createTaskFilter.finishedOn = this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.finishedOn);

      return this.taskDataService.createTaskFilter;
    }
  /* Prepare Create Task Filter Post Variable[start] */

   /* Prepare Create Task Post Variable[start] */
   prepareActivityTaskPost(): any {
    let postData = {
      "title": this.actStreamDataService.createTask.title,
      "description": this.actStreamDataService.createTask.description,
      "org_slug": this.cookieService.get('orgSlug'),
      "participants": this.actStreamDataService.createTask.participantIds,
      "responsible_person": this.actStreamDataService.createTask.responsiblePerson.responsiblePersonId,
      "approver": this.actStreamDataService.createTask.approver.approverSlug,
      "end_date": this.utilityService.convertToUnix(this.actStreamDataService.createTask.endDate),
      "start_date": this.utilityService.convertToUnix(this.actStreamDataService.createTask.startDate),
      "checklist": this.actStreamDataService.createTask.checklists,
      "repeatable":  this.actStreamDataService.createTask.repeat,
      "reminder": this.utilityService.convertToUnix(this.actStreamDataService.createTask.reminder),
      "parent_task": this.actStreamDataService.createTask.parentTask.parentTaskSlug,
      "responsible_person_change_time": this.actStreamDataService.createTask.responsiblePersonCanChangeTime,
      "approve_task_completed":  this.actStreamDataService.createTask.approveTaskCompleted,
      "priority": this.actStreamDataService.createTask.priority,
      "favourite": this.actStreamDataService.createTask.favourite,
      "is_template": this.actStreamDataService.createTask.isTemplate,
      "to_all_participants" : this.actStreamDataService.toUsers.toAllEmployee 
    }

    for(let i=0;i<this.taskDataService.createTask.assignees.length;i++){
      if(this.taskDataService.createTask.assignees[i].assigneeSlug)
        postData.participants.push(this.taskDataService.createTask.assignees[i].assigneeSlug)
    }

    // console.log(JSON.stringify(postData));
    return JSON.stringify(postData);
  }
  /* Prepare Create Task Post Variable[End]} */

}
