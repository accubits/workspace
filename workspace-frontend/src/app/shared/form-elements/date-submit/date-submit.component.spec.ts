import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DateSubmitComponent } from './date-submit.component';

describe('DateSubmitComponent', () => {
  let component: DateSubmitComponent;
  let fixture: ComponentFixture<DateSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DateSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DateSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
