import { Component, OnInit,Input,Output } from '@angular/core';
import { HrmSandboxService} from '../hrm.sandbox'
import { HrmDataService } from '../../shared/services/hrm-data.service';
import { HrmApiService } from '../../shared/services/hrm-api.service'

@Component({
  selector: 'app-tree-block',
  templateUrl: './tree-block.component.html',
  styleUrls: ['./tree-block.component.scss']
})
export class TreeBlockComponent implements OnInit {
  parentDepartment:any;
  headDepartment:any;
  rootDepartment:any;
  profileContent : boolean = false;
  membersList: boolean =false;
  selParentDept:string;

  constructor(
    private hrmSandboxService :HrmSandboxService,
    public hrmDataService: HrmDataService,
    public hrmApiService: HrmApiService
  ) { }

  @Input() treeData;
  localTree;
  activeTreeId;
  

  ngOnInit() {
    // this.localTree = this.treeData;
    //alert(JSON.stringify(this.treeData));
    //console.log(this.treeData);
    this.hrmSandboxService.getDepartments();
    this.hrmSandboxService.getAllEmployee();


  }
  showDetails(item){
    this.hrmDataService.deptDetails.show = true;
    this.hrmDataService.departmentDetailsSlug.departmentSlug = item.departmentSlug;
    this.hrmSandboxService.getDpmntDetails();
    //item.isActive = false;
  }
   showMemberPop($event,item){
     event.stopPropagation();
     item.isActive=!item.isActive;
    
  }
  
  showProfile(){
    this.profileContent =! this.profileContent;
  }

  
  displayChild(item){

    
    // for(let i = 0 ; i < item.child.length; i++){
    //   item.child[i].isPopupActive = !item.child[i].isPopupActive;
    // }
    this.activeTreeId =item.departmentId;
     //this.membersList = !this.membersList;
     this.find1([this.hrmDataService.orgTree],item.depth,item.departmentId);
   //  console.log(item.depth,item.departmentId, 'item.depth,item.departmentId');
     //this.find2(this.hrmDataService.orgTree,item.depth,item.departmentId);
     
     console.log(item, 'item');
  }
  // find2(arr,depth,parentId){
  //   console.log('parent',e.depth,e.departmentId);
  //   let i=0;
  //   for(var e of arr){
  //     console.log('parent',e.depth,e.departmentId);
  //     this.find2(e.child,e[i].depth,e[i].departmentId);
  //     i++;
  //   }
  // }
  find1(arr,depth,parentId) {
   
    for (let i=0;i<arr.length;i++) {
     
           if((parentId==arr[i].parentDepartmentId)&&(depth+1)==arr[i].depth){
            arr[i].isPopupActive = !arr[i].isPopupActive;
           }else if((arr[i].depth>=(depth+1))&&(parentId!=arr[i].parentDepartmentId)&&((depth+1)==arr[i].depth) ){
            console.log('depth greater');
             arr[i].isPopupActive=false;
           }

          //  if(arr[i].child.length){
          //      console.log('data ',arr[0]);
          //  }
          
           
            this.find1(arr[i].child,depth,parentId);
        //   if(arr[i].child.length){
        //     console.log(arr.depth,arr.departmentId,'arr.depth,arr.departmentId');
        //     this.find1(arr[i].child,arr[0].depth,arr[0].departmentId);
        //  }else{
        //   this.find1(arr[i].child,depth,parentId);
        //  }
        }
       
       
  }
  
  
  
  showEdit(item){
    this.hrmDataService.showEditDepartment.show = true;
    this.hrmDataService.hideField.show = false;
    // console.log('parenthdfjjfjeSlug',item)

    this.hrmDataService.editDeptSlug.slug = item.parentDepartmentSlug;
    this.hrmDataService.editDept.departmentSlug = item.departmentSlug;
    this. hrmDataService.editDept.name = item.departmentName;
    this.hrmDataService.editDept.parentDepartmentSlug = item.parentDepartmentSlug;

    this.parentDepartment = this.hrmDataService.allDepartmentsList.departments.filter(
    parentItem => parentItem.departmentSlug === item.parentDepartmentSlug) [0]
    this.hrmDataService.editDept.paretDeptName = this.parentDepartment.departmentName;
    this.hrmDataService.editDept.parentDepartmentSlug = this.parentDepartment.parentDepartmentSlug;

    this.headDepartment = this.hrmDataService.employeeList.list.filter(
      departmentHead => departmentHead.slug === departmentHead.departmentHeadUserSlug)[0]
      this.hrmDataService.editDept.departmentHeadName = this.headDepartment.employeeName;
      this.hrmDataService.editDept.employeeSlug = this.headDepartment.employeeSlug;    
      
      if((item.rootDepartmentSlug) && (item.parentDepartmentSlug) === null){
        this.hrmDataService.editDept.rootDepartmentSlug = item.departmentSlug;
      }

      else{
        this.hrmDataService.editDept.rootDepartmentSlug = item.rootDepartmentSlug;

      }
      item.isActive = false;

  }

  addChildDept(item){
    this.hrmDataService.showAddChild.show = true;
    this.hrmDataService.createDept.parentDepartmentSlug =  item.departmentSlug;
    this.hrmDataService.createDept.rootDepartmentSlug = item.rootDepartmentSlug;
    this.hrmDataService.deptMainName.paretDeptName = item.departmentName;
    item.isActive = false;
  }

  deleteDepartment(item){
    this.hrmDataService.deleteDepartment.departmentSlug = item.departmentSlug;
    this.hrmDataService.deleteTreeDepartment.show = true;
    item.isActive = false;

    // this.hrmSandboxService.deleteDepartment();
  }

  confirmDelete(){
      this.hrmSandboxService.deleteDepartment();
  }

  cancelDelete(){
    this.hrmDataService.deleteTreeDepartment.show =false;
  }
  memberPop(item){
    item.showMemberPopup = !item.showMemberPopup;
   
    this.hrmDataService.departmentDetailsSlug.departmentSlug = item.departmentSlug;
    this.hrmSandboxService.getDpmntDetails();
  }

}
