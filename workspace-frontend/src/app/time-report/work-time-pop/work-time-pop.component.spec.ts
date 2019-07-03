import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkTimePopComponent } from './work-time-pop.component';

describe('WorkTimePopComponent', () => {
  let component: WorkTimePopComponent;
  let fixture: ComponentFixture<WorkTimePopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkTimePopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkTimePopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
