import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkReportFilterComponent } from './work-report-filter.component';

describe('WorkReportFilterComponent', () => {
  let component: WorkReportFilterComponent;
  let fixture: ComponentFixture<WorkReportFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkReportFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkReportFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
