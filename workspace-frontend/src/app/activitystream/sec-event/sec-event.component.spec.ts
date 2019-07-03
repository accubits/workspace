import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecEventComponent } from './sec-event.component';

describe('SecEventComponent', () => {
  let component: SecEventComponent;
  let fixture: ComponentFixture<SecEventComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecEventComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecEventComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
