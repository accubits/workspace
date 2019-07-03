import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbWeekComponent } from './ab-week.component';

describe('AbWeekComponent', () => {
  let component: AbWeekComponent;
  let fixture: ComponentFixture<AbWeekComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbWeekComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbWeekComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
