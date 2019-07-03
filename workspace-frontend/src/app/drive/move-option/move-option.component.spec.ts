import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MoveOptionComponent } from './move-option.component';

describe('MoveOptionComponent', () => {
  let component: MoveOptionComponent;
  let fixture: ComponentFixture<MoveOptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MoveOptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MoveOptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
