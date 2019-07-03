import { Component, OnInit, Input} from '@angular/core';
import { HrmSandboxService} from '../hrm.sandbox'
import { HrmDataService } from '../../shared/services/hrm-data.service';
import { HrmApiService } from '../../shared/services/hrm-api.service'

@Component({
  selector: 'app-company',
  templateUrl: './company.component.html',
  styleUrls: ['./company.component.scss']
})
export class CompanyComponent implements OnInit {
  menu : boolean = false;
  profileContent : boolean = false;
  membersList: boolean = false;
  profileMembers:  boolean = false;
  subBranch : boolean = false;
  showList : boolean = false;
  libraryChildren: boolean = false;
  childDept : boolean = false;
  memberMenu: boolean = false;
  headDepartment:any;

  constructor(
    private hrmSandboxService :HrmSandboxService,
    public hrmDataService: HrmDataService,
    public hrmApiService: HrmApiService
  ) { }

  ngOnInit() {
    //console.log(this.hrmDataService.orgTree.child);
    //this.hrmSandboxService.getCompanyTree();
    this.hrmSandboxService.getDepartmentTree();
  }

  // showDeptDetails(slug){
  //   this.hrmDataService.deptDetails.show = true;
  //   this.hrmDataService.departmentDetailsSlug.departmentSlug = slug;
  //   this.hrmSandboxService.getDepartmentDetails();
  // }
  
  branch(slug){
    //alert("zzxc");
    this.subBranch =! this.subBranch;
    this.libraryChildren = false;
    this.childDept = false;
    //this.hrmApiService.getCompanyTree().data = "0";
    this.hrmDataService.companySubTree.departmentSlug = slug;
    this.hrmSandboxService.getCompanySubTree();
  }
  showPop($event){
    this.menu =! this.menu;
    event.stopPropagation();
  }

  showMembers($event){
    //event.stopPropagation();
    this.memberMenu =! this.memberMenu;
     for(let i = 0 ; i < this.hrmDataService.orgTree.child.length; i++){
       this.hrmDataService.orgTree.child[i].isPopupActive = !this.hrmDataService.orgTree.child[i].isPopupActive;
       this.membersList =! this.membersList;
    }
   
  }
  showEdit(){
    this.hrmDataService.showEditDepartment.show = true;
    this.hrmDataService.hideField.show = true;

    this.hrmDataService.editDeptSlug.slug = this.hrmDataService.orgTree.parentDepartmentSlug;
    this.hrmDataService.editDept.departmentSlug = this.hrmDataService.orgTree.departmentSlug;
    this.hrmDataService.editDept.name = this.hrmDataService.orgTree.departmentName;
    this.hrmDataService.editDept.parentDepartmentSlug = this.hrmDataService.orgTree.parentDepartmentSlug;
    this.hrmDataService.editDept.employeeSlug = this.hrmDataService.orgTree.departmentHeadEmployeeSlug;
    this.hrmDataService.editDept.departmentHeadName = this.hrmDataService.orgTree.departmentHeadEmail;
    this.menu = false;
  }
  showMemberEdit(){
    this.hrmDataService.editDeptPop.show = true;
  }
  closePop(){
    this.menu = false;
    this.hrmDataService.resetEditDept();
  }
  showDetails(slug){
    this.hrmDataService.deptDetails.show = true;
    this.hrmDataService.departmentDetailsSlug.departmentSlug = slug;
    this.hrmSandboxService.getDpmntDetails();
    this.menu = false;
  }
  addChildDept(){
    this.hrmDataService.showAddChild.show = true;
    this.hrmDataService.createDept.parentDepartmentSlug =  this.hrmDataService.orgTree.departmentSlug;
    this.hrmDataService.createDept.rootDepartmentSlug = this.hrmDataService.orgTree.departmentSlug;
    this.hrmDataService.deptMainName.paretDeptName = this.hrmDataService.orgTree.departmentName;
    this.menu = false;
  }

  closeMemberPop(){
    this.memberMenu = false;
  }
  showProfileList(){
    this.showList =! this.showList;
  }
  showProfile(slug){
    this.profileContent =! this.profileContent;
    this.hrmDataService.departmentDetailsSlug.departmentSlug = slug;
    this.hrmSandboxService.getDpmntDetails();
  }

  showLibChildren(){
    this.libraryChildren =! this.libraryChildren;
    this.subBranch = false;
    this.childDept = false;
  }
  // showMembers(){
  //   this.membersList =! this.membersList;
  //   this.subBranch = false;
  //   this.childDept = false;
  // }

  showProfileMembers(){
    this.profileMembers =! this.profileMembers;
  }

  showchildBranch2(){
    this.childDept =! this.childDept;
    this.subBranch = false;
    this.libraryChildren = false;
  }
}
