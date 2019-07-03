import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkReportDetailsComponent } from './work-report-details.component';

describe('WorkReportDetailsComponent', () => {
  let component: WorkReportDetailsComponent;
  let fixture: ComponentFixture<WorkReportDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkReportDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkReportDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
