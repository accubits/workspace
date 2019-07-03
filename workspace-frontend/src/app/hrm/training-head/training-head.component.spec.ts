import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrainingHeadComponent } from './training-head.component';

describe('TrainingHeadComponent', () => {
  let component: TrainingHeadComponent;
  let fixture: ComponentFixture<TrainingHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrainingHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrainingHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
