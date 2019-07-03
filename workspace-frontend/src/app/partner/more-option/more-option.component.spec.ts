import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MoreOptionComponent } from './more-option.component';

describe('MoreOptionComponent', () => {
  let component: MoreOptionComponent;
  let fixture: ComponentFixture<MoreOptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MoreOptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MoreOptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
