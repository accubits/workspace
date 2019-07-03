import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkTimeFilterComponent } from './work-time-filter.component';

describe('WorkTimeFilterComponent', () => {
  let component: WorkTimeFilterComponent;
  let fixture: ComponentFixture<WorkTimeFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkTimeFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkTimeFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
