
import { Component, OnInit, HostListener } from '@angular/core';
import { Configs } from '../../config';
import { TaskDataService} from '../../shared/services/task-data.service';
import { UtilityService } from '../../shared/services/utility.service'
import { TaskSandbox } from '../task.sandbox';


@Component({    selector: 'app-task-filter',
    templateUrl: './task-filter.component.html',
    styleUrls: ['./task-filter.component.scss']
})
export class TaskFilterComponent implements OnInit {
    public assetUrl = Configs.assetBaseUrl;
    activeParticiapntTab: string = 'all';
    activeRpTab: string = 'all';
    activeCrTab: string = 'all';
    filterParticipants: '';
    filterRespperson: '';
    filterCreatedBy: '';
    isValidated: boolean = true;
    status_popup: boolean = false;
    showPartList: boolean = false;
    shoRespList: boolean = false;
    showCreateList: boolean = false;
    filterStatus: boolean = false;
    //constructor(public taskDataService: TaskDataService) { }
    constructor(
        public taskDataService: TaskDataService,
        private taskSandbox: TaskSandbox,
        private utilityService: UtilityService,
    ) { }





    ngOnInit() {
        if(this.taskDataService.createTaskFilter.dueDate !== null){
            this.taskDataService.createTaskFilter.dueDate = new Date(this.taskDataService.createTaskFilter.dueDate * 1000);
        }

        if(this.taskDataService.createTaskFilter.startDate !== null){
            this.taskDataService.createTaskFilter.startDate = new Date(this.taskDataService.createTaskFilter.startDate * 1000);
        }

        if(this.taskDataService.createTaskFilter.finishedOn !== null){
            this.taskDataService.createTaskFilter.finishedOn = new Date(this.taskDataService.createTaskFilter.finishedOn  * 1000);
        }
       
        this.taskSandbox.getReposiblePerson();
        this.taskSandbox.getTaskStat();
        this.taskSandbox.getFilterLists();
    }


    @HostListener('document:click', ['$event'])

    documentClick(event: MouseEvent) {

        let modal = document.getElementById('save-popup');

        /*console.log(event.target);*/

        if (event.target == modal) {
            this.status_popup = false;
            this.showPartList = false;
            this.shoRespList = false;
            this.showCreateList = false;
        } else {

        }
    }

    //* Selecting participant[Start] */

    initOrChangeparticipantsList(): void {
        this.filterParticipants ? this.activeParticiapntTab = 'search' : this.activeParticiapntTab = 'all';
        this.taskDataService.responsiblePersons.searchText = this.filterParticipants;
        this.taskSandbox.getReposiblePerson(); // Using the same API for getting responsible person
    }

    selectPartcipants(participant): void {
        let existingParticpants = this.taskDataService.createTaskFilter.participant.filter(
            part => part.slug === participant.slug)[0];

        if (existingParticpants) {
            // toast to handle already added participant
            return;
        }
        this.taskDataService.createTaskFilter.participant.push({
            slug: participant.slug,
            name: participant.employee_name
        });
    }

    removePartcipants(participant): void {
        // Removing participant from participant list
        let existingParticpants = this.taskDataService.createTaskFilter.participant.filter(
            part => part.slug === participant.slug)[0];

        if (existingParticpants) {
            let idx = this.taskDataService.createTaskFilter.participant.indexOf(existingParticpants);
            if (idx !== -1) this.taskDataService.createTaskFilter.participant.splice(idx, 1);
        }
    }

    //* Selecting participant[End] */

    //* Selecting responsiblePerson[Start] */

    initOrChangeResppers(): void {
        this.filterRespperson ? this.activeRpTab = 'search' : this.activeRpTab = 'all';
        this.taskDataService.responsiblePersons.searchText = this.filterRespperson;
        this.taskSandbox.getReposiblePerson(); // Using the same API for getting responsible person
    }

    selectRespPerson(responsiblePerson): void {
        let existingParticpants = this.taskDataService.createTaskFilter.responsiblePerson.filter(
            part => part.slug === responsiblePerson.slug)[0];

        if (existingParticpants) {
            // toast to handle already added participant
            return;
        }
        this.taskDataService.createTaskFilter.responsiblePerson.push({
            slug: responsiblePerson.slug,
            name: responsiblePerson.employee_name
        });
    }

    removeResppers(responsiblePerson): void {
        // Removing participant from participant list
        let existingParticpants = this.taskDataService.createTaskFilter.responsiblePerson.filter(
            part => part.slug === responsiblePerson.slug)[0];

        if (existingParticpants) {
            let idx = this.taskDataService.createTaskFilter.responsiblePerson.indexOf(existingParticpants);
            if (idx !== -1) this.taskDataService.createTaskFilter.responsiblePerson.splice(idx, 1);
        }
    }

    //* Selecting responsiblePerson[Start] */

    //* Selecting Createdby[Start] */

    initOrChangeCreatedBy(): void {
        this.filterCreatedBy ? this.activeCrTab = 'search' : this.activeCrTab = 'all';
        this.taskDataService.responsiblePersons.searchText = this.filterCreatedBy;
        this.taskSandbox.getReposiblePerson(); // Using the same API for getting responsible person
    }

    selectCreatedBy(createdBy): void {
        let existingParticpants = this.taskDataService.createTaskFilter.createdBy.filter(
            part => part.slug === createdBy.slug)[0];

        if (existingParticpants) {
            // toast to handle already added participant
            return;
        }
        this.taskDataService.createTaskFilter.createdBy.push({
            slug: createdBy.slug,
            name: createdBy.employee_name
        });
    }

    removeCreatedBy(createdBy): void {
        // Removing participant from participant list
        let existingParticpants = this.taskDataService.createTaskFilter.createdBy.filter(
            part => part.slug === createdBy.slug)[0];

        if (existingParticpants) {
            let idx = this.taskDataService.createTaskFilter.createdBy.indexOf(existingParticpants);
            if (idx !== -1) this.taskDataService.createTaskFilter.createdBy.splice(idx, 1);
        }
    }

    //* Selecting Createdby[Start] */

    //* Selecting Status[Start] */
    selectStatus(taskStatus): void {
        let existingStatus = this.taskDataService.createTaskFilter.taskStatus.filter(
            part => part.slug === taskStatus.slug)[0];

        if (existingStatus) {
            // toast to handle already added participant
            return;
        }
        this.taskDataService.createTaskFilter.taskStatus.push({
            slug: taskStatus.slug,
            name: taskStatus.display_name
        });
    }

    removeStatus(taskStatus): void {
        // Removing status from status list
        let existingStatus = this.taskDataService.createTaskFilter.taskStatus.filter(
            part => part.slug === taskStatus.slug)[0];

        if (existingStatus) {
            let idx = this.taskDataService.createTaskFilter.taskStatus.indexOf(existingStatus);
            if (idx !== -1) this.taskDataService.createTaskFilter.taskStatus.splice(idx, 1);
        }
    }
    //* Selecting Status[End] */

    //* Selecting Filter[Start] */
    selectFilters(selectFilter): void {
        this.taskDataService.editTaskFilter.selectedTaskFilterSlug = selectFilter.filter_slug;
        this.taskSandbox.editFilter();
    }
    //* Selecting Filter[End] */

    //* Create New Filter[Start] */
    createNewFilter(): void {
        if (!this.validateNewFilter()) return;
        this.taskSandbox.createNewFilter();
    }
    //* Create New Filter[End] */

    //*Validate Filter
    validateNewFilter(): boolean {
        this.isValidated = true;
        // Validating Filter Name
        if (!this.taskDataService.createTaskFilter.filterName) this.isValidated = false;

        return this.isValidated;
    }
    //*Validate Filter

    //* Apply Filter[Start] */
    applyFilter(): void {
        this.taskDataService.createTaskFilter.dueDate = this.taskDataService.createTaskFilter.dueDate ? this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.dueDate) : null;
        this.taskDataService.createTaskFilter.startDate = this.taskDataService.createTaskFilter.startDate ? this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.startDate) : null;
        this.taskDataService.createTaskFilter.finishedOn = this.taskDataService.createTaskFilter.finishedOn ? this.utilityService.convertToUnix(this.taskDataService.createTaskFilter.finishedOn) : null;
        this.taskDataService.getTasks.isFilterdBy = true;
        this.taskDataService.getTasks.page = 1 ;
        this.taskSandbox.getTaskList();
        this.taskDataService.taskFilterpopup.show = false
    }
    //* Apply Filter[End] */

    //* Show FilterList[End] */
    pushToFilter(filterObj): void {
        this.taskDataService.selectedFilters.itemsInFilter.push(filterObj)
    }
    //* Show FilterList[End] */

    //* Delete Filter[Start] */
    deleteFilter(selectFilter): void {
        event.stopPropagation();
        this.taskDataService.deleteTaskFilter.deleteTaskFilterSlug = selectFilter.filter_slug;
        this.taskSandbox.deleteFilter();
    }
    //* Delete Filter[Start] */

    resetFilter(): void {
        this.taskDataService.getTasks.isFilterdBy = false;
        this.taskSandbox.getTaskList();
        this.taskDataService.resetFilter();
    }


}

