import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { HrmRoutingModule } from './hrm-routing.module';
import { HrmSandboxService } from './hrm.sandbox';
import { HrmComponent } from './hrm.component';
import { SharedModule } from '../shared/shared.module';
import { LeaveHeadComponent } from './leave-head/leave-head.component';
import { LeaveComponent } from './leave/leave.component';
import { LeaveNavBarComponent } from './leave-nav-bar/leave-nav-bar.component';
import { LeaveTableComponent } from './leave-table/leave-table.component';
import { RequestDetailComponent } from './request-detail/request-detail.component';
import { LeaveRequestComponent } from './leave-request/leave-request.component';
import { EmployeeComponent } from './employee/employee.component';
import { EmpHeadComponent } from './emp-head/emp-head.component';
import { EmpTableHeadComponent } from './emp-table-head/emp-table-head.component';
import { CompanyComponent } from './company/company.component';
import { EmpTableComponent } from './emp-table/emp-table.component';
import { NewUserComponent } from './new-user/new-user.component';
import { AbsenceChartComponent } from './absence-chart/absence-chart.component';
import { HolidaysComponent } from './holidays/holidays.component';
import { LeaveTypeComponent} from './leave-type/leave-type.component';
import { LeaveTypeDetailComponent } from './leave-type-detail/leave-type-detail.component';
import { NewLeaveTypeComponent } from './new-leave-type/new-leave-type.component';
import { NewDeptComponent } from './new-dept/new-dept.component';
import { AddHolidayComponent } from './add-holiday/add-holiday.component';
import { AbsenceHeaderComponent } from './absence-header/absence-header.component';
import { AbsenceDetailComponent } from './absence-detail/absence-detail.component';
import { AbsenceFilterComponent } from './absence-filter/absence-filter.component';
import { AddNewAbsenceComponent } from './add-new-absence/add-new-absence.component';
import { ProfileInfoComponent } from './profile-info/profile-info.component';
import { ProfileLeaveComponent } from './profile-leave/profile-leave.component';
import { ProfilePerformComponent } from './profile-perform/profile-perform.component';
import { EmpDetailComponent } from './emp-detail/emp-detail.component';
import { InformDetailComponent } from './inform-detail/inform-detail.component';
import { LeaveInformComponent } from './leave-inform/leave-inform.component';
import { PerformanceComponent } from './performance/performance.component';
import { EditEmpProfileComponent } from './edit-emp-profile/edit-emp-profile.component';
import { AddMemberComponent } from './add-member/add-member.component';
import { TrainingComponent } from './training/training.component';
import { TrainingHeadComponent } from './training-head/training-head.component';
import { TrainingNavComponent } from './training-nav/training-nav.component';
import { MyTrainingComponent } from './my-training/my-training.component';
import { TrainingRequestTableComponent } from './training-request-table/training-request-table.component';
import { OngoingTrainingComponent } from './ongoing-training/ongoing-training.component';
import { CompletedTrainingComponent } from './completed-training/completed-training.component';
import { FeedbackComponent } from './feedback/feedback.component';
import { TrainingDetailComponent } from './training-detail/training-detail.component';
import { TrainingRequestComponent } from './training-request/training-request.component';
import { OngoingTrainingDetailsComponent } from './ongoing-training-details/ongoing-training-details.component';
import { OngoingTrainRequestDetailComponent } from './ongoing-train-request-detail/ongoing-train-request-detail.component';
import { CompletedDetailComponent } from './completed-detail/completed-detail.component';
import { FeedbackFormComponent } from './feedback-form/feedback-form.component';
import { TrainingRequestFormComponent } from './training-request-form/training-request-form.component';
import { MoreOptionComponent } from './more-option/more-option.component';
import { DeleteConfirmComponent } from './delete-confirm/delete-confirm.component';
import {FormsSandbox} from './../forms/forms.sandbox'
import { PerformanceDetailComponent } from './performance-detail/performance-detail.component';
import { PerformanceHeadComponent } from './performance-head/performance-head.component';
import { NewModuleComponent } from './new-module/new-module.component';
import { QuestionTypeRatingComponent } from './question-type-rating/question-type-rating.component';
import { QuestionTypeAnswerComponent } from './question-type-answer/question-type-answer.component';
import { PerformanceNavbarComponent } from './performance-navbar/performance-navbar.component';
import { KraModulesComponent } from './kra-modules/kra-modules.component';
import { DeptPopComponent } from './dept-pop/dept-pop.component';
import { FormElementsModule } from '../shared/form-elements/form_elements.module';
import { PublishFormComponent } from './publish-form/publish-form.component';
import { KraModuleDetailComponent } from './kra-module-detail/kra-module-detail.component';
import { EditDeptComponent } from './edit-dept/edit-dept.component';
import { ApproveTableComponent } from './approve-table/approve-table.component';
import { ActiveTrainingDetailComponent } from './active-training-detail/active-training-detail.component';
import { CourseFormComponent } from './course-form/course-form.component';
import { ReviewRequestComponent} from './review-request/review-request.component';
import { ReviewRequestDetailComponent } from './review-request-detail/review-request-detail.component';
import { ReviewHistoryComponent } from './review-history/review-history.component';
import { ReviewHistoryDetailComponent } from './review-history-detail/review-history-detail.component';
import { AppraisalCycleComponent } from './appraisal-cycle/appraisal-cycle.component';
import { MyPerformanceComponent } from './my-performance/my-performance.component';
import { AddAppraisalPopComponent } from './add-appraisal-pop/add-appraisal-pop.component';
import { MyPerformanceDetailComponent } from './my-performance-detail/my-performance-detail.component';
import { TreeBlockComponent } from './tree-block/tree-block.component';
import { AppraisalAnnualReviewComponent } from './appraisal-annual-review/appraisal-annual-review.component';
import { ActiveDetailPopComponent } from './active-detail-pop/active-detail-pop.component';
import { FinishedPopComponent } from './finished-pop/finished-pop.component';
import { OverviewComponent } from './overview/overview.component';
import { FormsModule } from '../forms/forms.module';
//import { NewFormComponent } from '../forms/new-form/new-form.component';
import { FeedbackFormCreationComponent } from './feedback-form-creation/feedback-form-creation.component';
import { FormCreationWrapComponent } from './form-creation-wrap/form-creation-wrap.component';
import { SettingsComponent } from './settings/settings.component';
import { SettingsDetailsComponent } from './settings-details/settings-details.component';
import { AppraisalResultComponent } from './appraisal-result/appraisal-result.component';
import { AppraisalFormComponent } from './appraisal-form/appraisal-form.component';
import { AnnualReviewResultComponent } from './annual-review-result/annual-review-result.component';
import { AnnualReviewResultDetailComponent } from './annual-review-result-detail/annual-review-result-detail.component';
import { AddChildDepartmentComponent } from './add-child-department/add-child-department.component';




@NgModule({
  imports: [
    CommonModule,
    HrmRoutingModule,
    PerfectScrollbarModule,
    SharedModule,
    FormElementsModule,
    FormsModule,
  ],
  providers: [HrmSandboxService, FormsSandbox],
  // declarations: [HrmComponent, LeaveHeadComponent, LeaveComponent, LeaveNavBarComponent, LeaveTypeComponent, LeaveTableComponent, RequestDetailComponent, LeaveRequestComponent, EmployeeComponent, EmpHeadComponent, EmpTableHeadComponent, CompanyComponent,  NewUserComponent, EmpTableComponent, AbsenceChartComponent, HolidaysComponent, LeaveTypeDetailComponent, NewLeaveTypeComponent, NewDeptComponent, AddHolidayComponent, AbsenceHeaderComponent, AbsenceDetailComponent, AbsenceFilterComponent, AddNewAbsenceComponent, ProfileInfoComponent, ProfileLeaveComponent, ProfilePerformComponent, EmpDetailComponent, InformDetailComponent, LeaveInformComponent, PerformanceComponent, EditEmpProfileComponent, AddMemberComponent, TrainingComponent, TrainingHeadComponent, TrainingNavComponent, MyTrainingComponent, TrainingRequestTableComponent, OngoingTrainingComponent, CompletedTrainingComponent, FeedbackComponent, TrainingDetailComponent, TrainingRequestComponent, OngoingTrainingDetailsComponent, OngoingTrainRequestDetailComponent, CompletedDetailComponent, FeedbackFormComponent, TrainingRequestFormComponent, MoreOptionComponent, DeleteConfirmComponent, NewFormComponent, PerformanceDetailComponent, PerformanceHeadComponent, NewModuleComponent, FormCreationComponent, FormWrapLeftComponent, ContentWrapRightComponent, PublishFormComponent, QuestionTypeRatingComponent, QuestionTypeAnswerComponent, PerformanceNavbarComponent, KraModulesComponent, KraModuleDetailComponent],
  // providers: [HrmSandboxService,FormsSandbox],
  declarations: [HrmComponent, LeaveHeadComponent, FeedbackFormCreationComponent, FormCreationWrapComponent, LeaveComponent, LeaveNavBarComponent, LeaveTypeComponent, LeaveTableComponent, RequestDetailComponent, LeaveRequestComponent, EmployeeComponent, EmpHeadComponent, EmpTableHeadComponent, CompanyComponent,  NewUserComponent, EmpTableComponent, AbsenceChartComponent, HolidaysComponent, LeaveTypeDetailComponent, NewLeaveTypeComponent, NewDeptComponent, AddHolidayComponent, AbsenceHeaderComponent, AbsenceDetailComponent, AbsenceFilterComponent, AddNewAbsenceComponent, ProfileInfoComponent, ProfileLeaveComponent, ProfilePerformComponent, EmpDetailComponent, InformDetailComponent, LeaveInformComponent, PerformanceComponent, EditEmpProfileComponent, AddMemberComponent, TrainingComponent, TrainingHeadComponent, TrainingNavComponent, MyTrainingComponent, TrainingRequestTableComponent, OngoingTrainingComponent, CompletedTrainingComponent, FeedbackComponent, TrainingDetailComponent, TrainingRequestComponent, OngoingTrainingDetailsComponent, OngoingTrainRequestDetailComponent, CompletedDetailComponent, FeedbackFormComponent, TrainingRequestFormComponent, MoreOptionComponent, DeleteConfirmComponent, PerformanceDetailComponent, PerformanceHeadComponent, NewModuleComponent, DeptPopComponent, PublishFormComponent, QuestionTypeRatingComponent, QuestionTypeAnswerComponent, PerformanceNavbarComponent, KraModulesComponent, KraModuleDetailComponent, EditDeptComponent, ApproveTableComponent, ActiveTrainingDetailComponent, CourseFormComponent, ReviewRequestComponent, ReviewRequestDetailComponent, ReviewHistoryComponent, ReviewHistoryDetailComponent, AppraisalCycleComponent, AddAppraisalPopComponent, TreeBlockComponent,  MyPerformanceComponent, MyPerformanceDetailComponent, AppraisalAnnualReviewComponent, ActiveDetailPopComponent, FinishedPopComponent, OverviewComponent, SettingsComponent, SettingsDetailsComponent, AppraisalResultComponent, AppraisalFormComponent, AnnualReviewResultComponent, AnnualReviewResultDetailComponent, AddChildDepartmentComponent],
  })
export class HrmModule { }
