import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkReportAddComponent } from './work-report-add.component';

describe('WorkReportAddComponent', () => {
  let component: WorkReportAddComponent;
  let fixture: ComponentFixture<WorkReportAddComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkReportAddComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkReportAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
